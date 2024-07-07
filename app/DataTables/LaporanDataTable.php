<?php

namespace App\DataTables;

use App\Models\Laporan;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LaporanDataTable extends DataTable
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
            ->filterColumn('kasir', function ($query, $keyword) {
                $query->whereRaw('LOWER(users.name) like ?', ["%{$keyword}%"]);
            })
            ->editColumn('no_laporan', function (Laporan $model) {
                return $model->no_laporan;
            })
            ->editColumn('kasir', function (Laporan $model) {
                return $model->kasir->name;
            })
            ->editColumn('shift_kerja', function (Laporan $model) {
                return $model->kasir->info->shift;
            })
            ->editColumn('total', function (Laporan $model) {
                return "Rp." . convertRupiah($model->total);
            })
            ->editColumn('created_at', function (Laporan $model) {
                return Carbon::parse($model->created_at)->format('d-M-Y H:i');
            })
            ->addColumn('action', function (Laporan $model) {
                return view('pages.laporan._action-menu', [
                    'destroyUrl' => route('laporan.destroy', $model->id),
                    'showUrl' => route('laporan.show', $model->no_laporan),
                    'editUrl' => route('laporan.edit', $model->id),
                    'model' => $model
                ]);
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Laporan $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Laporan $model)
    {
        return $model->newQuery()
            ->leftJoin('user_infos', 'laporans.user_id', '=', 'user_infos.user_id')
            ->leftJoin('users', 'laporans.user_id', '=', 'users.id')
            ->select(
                'laporans.*',
                'user_infos.shift as shift_kerja',
                'users.name'
            )
            ->when(intval($this->shift), function ($query, $shift) {
                return $query->where('user_infos.shift', $shift);
            })
            ->when($this->from_date, function ($query, $fromDate) {
                return $query->whereDate('laporans.created_at', '>=', Carbon::parse($fromDate)->format('Y-m-d H:i:s'));
            })
            ->when($this->end_date, function ($query, $endDate) {
                return $query->whereDate('laporans.created_at', '<=', Carbon::parse($endDate)->format('Y-m-d H:i:s'));
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
            ->setTableId('category-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('laporans.table'))
            ->stateSave(true)
            ->orderBy(2)
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
            Column::make('no_laporan'),
            Column::make('kasir'),
            Column::make('shift_kerja'),
            Column::make('total'),
            Column::make('created_at')->title('Tanggal'),
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
        return 'Laporan_' . date('YmdHis');
    }
}
