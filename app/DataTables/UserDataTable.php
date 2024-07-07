<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\DB;

class UserDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->filterColumn('shift_kerja', function ($query, $keyword) {
                $query->whereRaw('LOWER(user_infos.shift) like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('phone', function ($query, $keyword) {
                $query->whereRaw('LOWER(user_infos.phone) like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('address', function ($query, $keyword) {
                $query->whereRaw('LOWER(user_infos.address) like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('role', function ($query, $keyword) {
                $query->whereRaw('LOWER(roles_concat.roles) like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('permissions', function ($query, $keyword) {
                $query->whereRaw('LOWER(permissions_concat.permissions) like ?', ["%{$keyword}%"]);
            })
            ->editColumn('name', function (User $user) {
                return $user->name;
            })
            ->editColumn('shift_kerja', function (User $user) {
                return $user->info->shift;
            })
            ->editColumn('username', function (User $user) {
                return $user->username;
            })
            ->editColumn('role', function (User $user) {
                return $user->getRoleNames()->first();
            })
            ->editColumn('permissions', function (User $user) {
                $rolePermissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();
                return implode(', ', array_unique($rolePermissions));
            })
            ->editColumn('phone', function (User $user) {
                return $user->info->phone;
            })
            ->editColumn('address', function (User $user) {
                return $user->info->address;
            })
            ->editColumn('created_at', function (User $user) {
                return $user->created_at->format('d M, Y H:i:s');
            })
            ->addColumn('action', function (User $model) {
                return view('pages.users._action-menu', [
                    'destroyUrl' => route('users.destroy', $model->id),
                    'showUrl' => route('users.show', $model->id),
                    'editUrl' => route('users.edit', $model->id),
                    'model' => $model
                ]);
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $rolesConcat = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('model_has_roles.model_id', DB::raw('GROUP_CONCAT(roles.name) as roles'))
            ->groupBy('model_has_roles.model_id');

        $permissionsConcat = DB::table('role_has_permissions')
            ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->select('role_has_permissions.role_id', DB::raw('GROUP_CONCAT(permissions.name) as permissions'))
            ->groupBy('role_has_permissions.role_id');

        return $model->newQuery()
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoinSub($rolesConcat, 'roles_concat', function ($join) {
                $join->on('users.id', '=', 'roles_concat.model_id');
            })
            ->leftJoinSub($permissionsConcat, 'permissions_concat', function ($join) {
                $join->on('model_has_roles.role_id', '=', 'permissions_concat.role_id');
            })
            ->leftJoin('user_infos', 'users.id', '=', 'user_infos.user_id')
            ->select(
                'users.id',
                'users.name',
                'user_infos.shift as shift_kerja',
                'users.username',
                'users.created_at',
                'user_infos.address as address',
                'user_infos.phone as phone',
                'roles_concat.roles as role',
                'permissions_concat.permissions as permissions'
            )
            ->groupBy('users.id', 'users.name', 'user_infos.shift', 'users.username', 'users.created_at', 'user_infos.address', 'user_infos.phone', 'roles_concat.roles', 'permissions_concat.permissions')
            ->where('users.id', '!=', Auth::id())
            ->when(intval($this->shift), function ($query, $shift) {
                return $query->where('user_infos.shift', $shift);
            });
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('user-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('users.table'))
            ->orderBy(1)
            ->responsive()
            ->autoWidth(false)
            ->parameters(['scrollX' => true])
            ->addTableClass('align-middle table-row-dashed fs-6 gy-5');
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('name'),
            Column::make('shift_kerja'),
            Column::make('username'),
            Column::make('role'),
            Column::make('permissions'),
            Column::make('phone'),
            Column::make('address')->title('alamat'),
            Column::make('created_at')->title('tanggal'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center d-flex')
                ->responsivePriority(-1),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'User_' . date('YmdHis');
    }
}
