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
            ->rawColumns(['kategori', 'harga', 'stok', 'select'])
            ->addColumn('select', function (Product $model) {
                return '
                    <input type="checkbox" name="id_product[]" value="' . $model->id . '">
                ';
            })
            ->setRowId('id')
            ->editColumn('kode', function (Product $model) {
                return $model->kode;
            })
            ->editColumn('nama_produk', function (Product $model) {
                return $model->nama_produk;
            })
            ->editColumn('kategori_id', function (Product $model) {
                return $model->category->nama;
            })
            ->editColumn('satuan_id', function (Product $model) {
                return $model->satuan->nama;
            })
            ->editColumn('harga', function (Product $model) {
                return $model->harga;
            })
            ->editColumn('diskon', function (Product $model) {
                return $model->diskon . ' %';
            })
            ->editColumn('stok', function (Product $model) {
                return $model->stok . " " .  $model->satuan->nama;
            })
            ->editColumn('foto', function (Product $model) {
                $content = $model->foto;

                return view('pages.products._details', compact('content'));
            })
            ->addColumn('action', function (Product $model) {
                return view('pages.products._action-menu', [
                    'destroyUrl' => route('products.destroy', $model->id),
                    'showUrl' => route('products.show', $model->id),
                    'editUrl' => route('products.edit', $model->id),
                    'model' => $model
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
            Column::make('select')
                ->searchable(false)
                ->sortable(false)
                ->title('<input type="checkbox" id="select-all">'),
            Column::make('id'),
            Column::make('kode'),
            Column::make('nama_produk'),
            Column::make('kategori_id')->title('kategori'),
            Column::make('satuan_id')->title('satuan'),
            Column::make('harga'),
            Column::make('diskon'),
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
