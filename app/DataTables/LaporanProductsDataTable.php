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
            ->filterColumn('nama_produk', function ($query, $keyword) {
                $query->where(function ($subquery) use ($keyword) {
                    $subquery->whereRaw('LOWER(products.nama_produk) like ?', ["%{$keyword}%"])
                        ->orWhereRaw('LOWER(promo_bundles.nama_bundel) like ?', ["%{$keyword}%"]);
                });
            })
            ->editColumn('nama_produk', function (LaporanProducts $item) {
                if ($item->product_id) {
                    return $item->product->nama_produk;
                } else {
                    return $item->promoBundle->nama_bundel;
                }
            })
            ->editColumn('jumlah', function (LaporanProducts $item) {
                return $item->jumlah;
            })
            ->editColumn('satuan', function (LaporanProducts $item) {
                return $item->satuan;
            })
            ->editColumn('sub_total', function (LaporanProducts $item) {
                return $item->sub_total;
            });

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

        // Use left join for optional product or promo_bundle relationship
        return $model->newQuery()
            ->leftJoin('promo_bundles', function ($join) use ($model) {
                $join->on('laporan_products.promo_bundle_id', '=', 'promo_bundles.id')
                    ->whereNull('laporan_products.product_id');
            })
            ->leftJoin('products', function ($join) use ($model) {
                $join->on('laporan_products.product_id', '=', 'products.id')
                    ->whereNotNull('laporan_products.product_id');
            })
            ->where('laporan_products.laporan_id', $laporan->id)
            ->select([
                'laporan_products.*',
                'products.nama_produk',
                'promo_bundles.nama_bundel',
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
            Column::make('nama_produk'),
            Column::make('jumlah'),
            Column::make('satuan'),
            Column::make('sub_total'),
        ];

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
