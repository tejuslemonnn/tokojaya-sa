<?php

namespace App\DataTables;

use App\Models\Product;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductsDataTable extends DataTable
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
            ->rawColumns(['kategori', 'harga', 'stok'])
            ->setRowId('id')
            ->editColumn('kode', function (Product $model) {
                return $model->kode;
            })
            ->editColumn('nama_barang', function (Product $model) {
                return $model->nama_barang;
            })
            ->editColumn('kategori_id', function (Product $model) {
                return $model->category->nama;
            })
            ->editColumn('harga', function (Product $model) {
                return $model->harga;
            })
            ->editColumn('stok', function (Product $model) {
                return $model->stok;
            })
            ->editColumn('foto', function (Product $model) {
                $content = $model->foto;

                return view('pages.products._details', compact('content'));
            })
            ->addColumn('action', function (Product $model) {
                return view('partials.widgets.master._action-menu', [
                    'destroyUrl' => route('products.destroy', $model->id),
                    'showUrl' => route('products.show', $model->id),
                    'editUrl' => route('products.edit', $model->id),
                ]);
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $model)
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
            ->setTableId('products-table')
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
            Column::make('kode'),
            Column::make('nama_barang'),
            Column::make('kategori_id'),
            Column::make('harga'),
            Column::make('stok'),
            Column::make('foto')->addClass('none'),
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
        return 'Products_' . date('YmdHis');
    }
}
