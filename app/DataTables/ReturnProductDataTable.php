<?php

namespace App\DataTables;

use App\Models\ReturnPenjualan;
use App\Models\ReturnProduct;
use Request;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ReturnProductDataTable extends DataTable
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
            ->editColumn('nama_produk', function (ReturnProduct $model) {
                return $model->product->nama_produk;
            })
            ->editColumn('jumlah', function (ReturnProduct $model) {
                return $model->jumlah;
            })
            ->editColumn('satuan', function (ReturnProduct $model) {
                return $model->satuan;
            });
            // ->addColumn('action', 'returnproduct.action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ReturnProduct $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ReturnProduct $model, Request $request)
    {
        $noReturn = $this->no_return ?? request()->segment(2);
        $return = ReturnPenjualan::where('no_return', $noReturn)->first();

        return $model->newQuery()
            ->join('products', 'return_products.product_id', '=', 'products.id')
            ->where('return_products.return_penjualan_id', $return->id)
            ->select([
                'return_products.*',
                'products.nama_produk',
            ]);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('datatableReturn')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave(true)
            ->orderBy(0)
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
            Column::make('nama_produk'),
            Column::make('jumlah'),
            Column::make('satuan'),
            // Column::computed('action')
            //     ->exportable(false)
            //     ->printable(false)
            //     ->addClass('text-center d-flex')
            //     ->responsivePriority(-1),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'ReturnProduct_' . date('YmdHis');
    }
}
