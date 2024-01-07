<?php

namespace App\DataTables;

use App\Models\Satuan;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SatuanDataTable extends DataTable
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
            ->editColumn('nama', function (Satuan $model) {
                return $model->nama;
            })
            ->editColumn('kode', function (Satuan $model) {
                return $model->kode;
            })
            ->addColumn('action', function (Satuan $model) {
                return view('pages.satuans._action-menu', [
                    'destroyUrl' => route('satuans.destroy', $model->id),
                    'showUrl' => route('satuans.show', $model->id),
                    'editUrl' => route('satuans.edit', $model->id),
                    'model' => $model
                ]);
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Satuan $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Satuan $model)
    {
        return $model->newQuery();
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
            ->minifiedAjax()
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
            Column::make('id'),
            Column::make('nama'),
            Column::make('kode'),
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
        return 'Satuan_' . date('YmdHis');
    }
}
