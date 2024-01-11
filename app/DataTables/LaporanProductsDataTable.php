<?php

namespace App\DataTables;

use App\Models\Laporan;
use Illuminate\Http\Request;
use App\Models\LaporanProducts;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LaporanProductsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */

    public function dataTable($query)
    {
        $dataTable = datatables()
            ->eloquent($query)
            ->editColumn('nama_produk', function (LaporanProducts $item) {
                return $item->product->nama_produk;
            })
            ->editColumn('jumlah', function (LaporanProducts $item) {
                return $item->jumlah;
            })
            ->editColumn('satuan', function (LaporanProducts $item) {
                return $item->satuan;
            })
            ->editColumn('sub_total', function (LaporanProducts $item) {
                return $item->sub_total;
            })
            ->editColumn('return_jumlah', function (LaporanProducts $item) {
                $jumlah = optional($item->return)->jumlah ?? '';
                $satuan = optional($item->return)->satuan ?? '';

                return $jumlah . ' ' . $satuan;
            });

        if (auth()->user()->hasPermissionTo('manage shop')) {
            $dataTable->addColumn('action', function (LaporanProducts $item) {
                return view('pages.laporan._action-menu-detail', [
                    'item' => $item
                ]);
            });
        }

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\LaporanProduct $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(LaporanProducts $model, Request $request)
    {
        $noLaporan = $this->no_laporan ?? $request->segment(2);

        $laporan = Laporan::where('no_laporan', $noLaporan)->first();

        return $model->newQuery()
            ->join('products', 'laporan_products.product_id', '=', 'products.id')
            ->leftJoin('return_products', 'laporan_products.id', '=', 'return_products.laporan_product_id')
            ->where('laporan_products.laporan_id', $laporan->id)
            ->select([
                'laporan_products.*',
                'products.nama_produk',
                'return_products.jumlah as return_jumlah, return_products.satuan as return_satuan',
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
        $columns = [
            Column::make('id'),
            Column::make('nama_produk'),
            Column::make('jumlah'),
            Column::make('satuan'),
            Column::make('sub_total'),
            Column::make('return_jumlah')->title('return'),
        ];

        if (auth()->user()->hasPermissionTo('manage shop')) {
            $columns[] = Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center d-flex')
                ->responsivePriority(-1);
        }

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'LaporanProducts_' . date('YmdHis');
    }
}
