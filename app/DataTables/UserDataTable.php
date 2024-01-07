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
            ->setRowId('id')
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
        return $model->newQuery()
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->leftJoin('role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id')
            ->leftJoin('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->leftJoin('user_infos', 'users.id', '=', 'user_infos.user_id')
            ->select(
                'users.id',
                'users.username',
                'users.created_at',
                'user_infos.address',
                'user_infos.phone',
                DB::raw('GROUP_CONCAT(DISTINCT roles.name) as role'),
                DB::raw('GROUP_CONCAT(DISTINCT permissions.name) as permissions')
            )
            ->groupBy('users.id', 'users.username', 'users.created_at', 'user_infos.address', 'user_infos.phone')
            ->where('users.id', '!=', Auth::id());
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
            ->minifiedAjax()
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
            Column::make('id'),
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
