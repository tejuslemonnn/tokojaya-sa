<?php

namespace App\DataTables;

use App\Models\PromoBundle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PromoBundleDataTable extends DataTable
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
            ->rawColumns(['beli_produk', 'gratis_produk', 'harga_asli'])
            ->filterColumn('mulai_berlaku', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(mulai_berlaku, '%d %M %Y') like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('selesai_berlaku', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(selesai_berlaku, '%d %M %Y') like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('beli_produk', function ($query, $keyword) {
                $query->whereRaw("EXISTS (
                    SELECT 1
                    FROM promo_bundle_items
                    JOIN products ON promo_bundle_items.product_id = products.id
                    WHERE promo_bundle_items.promo_bundle_id = promo_bundles.id
                    AND promo_bundle_items.tipe = 'Beli'
                    AND LOWER(products.nama_produk) LIKE ?
                )", ["%{$keyword}%"]);
            })
            ->filterColumn('gratis_produk', function ($query, $keyword) {
                $query->whereRaw("EXISTS (
                    SELECT 1
                    FROM promo_bundle_items
                    JOIN products ON promo_bundle_items.product_id = products.id
                    WHERE promo_bundle_items.promo_bundle_id = promo_bundles.id
                    AND promo_bundle_items.tipe = 'Gratis'
                    AND LOWER(products.nama_produk) LIKE ?
                )", ["%{$keyword}%"]);
            })
            ->editColumn('nama_bundel', function (PromoBundle $promoBundle) {
                return $promoBundle->nama_bundel;
            })
            ->editColumn('deskripsi', function (PromoBundle $promoBundle) {
                return $promoBundle->deskripsi;
            })
            ->editColumn('beli_produk', function (PromoBundle $promoBundle) {
                $beliProduk = $promoBundle->promoBundleItems->where('tipe', 'Beli');
                $list = $beliProduk->map(function ($item) {
                    return "{$item->product->nama_produk} {$item->qty} {$item->product->satuan->nama}";
                })->implode('</li><li>');

                return '<ul><li>' . $list . '</li></ul>';
            })
            ->editColumn('gratis_produk', function (PromoBundle $promoBundle) {
                $gratisProduk = $promoBundle->promoBundleItems->where('tipe', 'Gratis');
                $list = $gratisProduk->map(function ($item) {
                    return "{$item->product->nama_produk} {$item->qty} {$item->product->satuan->nama}";
                })->implode('</li><li>');

                return '<ul><li>' . $list . '</li></ul>';
            })
            ->editColumn('mulai_berlaku', function (PromoBundle $promoBundle) {
                return Carbon::parse($promoBundle->mulai_berlaku)->format('d M Y');
            })
            ->editColumn('selesai_berlaku', function (PromoBundle $promoBundle) {
                return Carbon::parse($promoBundle->selesai_berlaku)->format('d M Y');
            })
            ->editColumn('kode_barcode', function (PromoBundle $promoBundle) {
                return $promoBundle->kode_barcode;
            })
            ->editColumn('harga_asli', function (PromoBundle $promoBundle) {
                return '<span style="text-decoration: line-through;">' . "Rp." . convertRupiah($promoBundle->harga_asli) . '</span>';
            })
            ->editColumn('harga_promo', function (PromoBundle $promoBundle) {
                return "Rp." . convertRupiah($promoBundle->harga_promo);
            })
            ->editColumn('created_at', function (PromoBundle $promoBundle) {
                return $promoBundle->created_at->format('d M, Y H:i:s');
            })
            ->addColumn('action', function (PromoBundle $model) {
                return view('pages.promoBundle._action-menu', [
                    'destroyUrl' => route('promo-bundle.destroy', $model->id),
                    'showUrl' => route('promo-bundle.show', $model->id),
                    'editUrl' => route('promo-bundle.edit', $model->id),
                    'model' => $model
                ]);
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\PromoBundle $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PromoBundle $model)
    {
        return $model->newQuery()
            ->leftJoin('promo_bundle_items as beli_items', function ($join) {
                $join->on('promo_bundles.id', '=', 'beli_items.promo_bundle_id')
                    ->where('beli_items.tipe', 'Beli');
            })
            ->leftJoin('promo_bundle_items as gratis_items', function ($join) {
                $join->on('promo_bundles.id', '=', 'gratis_items.promo_bundle_id')
                    ->where('gratis_items.tipe', 'Gratis');
            })
            ->leftJoin('products as beli_products', 'beli_items.product_id', '=', 'beli_products.id')
            ->leftJoin('products as gratis_products', 'gratis_items.product_id', '=', 'gratis_products.id')
            ->select(
                'promo_bundles.id',
                'promo_bundles.nama_bundel',
                'promo_bundles.deskripsi',
                'promo_bundles.mulai_berlaku',
                'promo_bundles.selesai_berlaku',
                'promo_bundles.kode_barcode',
                'promo_bundles.harga_asli',
                'promo_bundles.harga_promo',
                'promo_bundles.created_at',
                DB::raw('GROUP_CONCAT(DISTINCT beli_products.nama_produk SEPARATOR ", ") as beli_produk'),
                DB::raw('GROUP_CONCAT(DISTINCT gratis_products.nama_produk SEPARATOR ", ") as gratis_produk')
            )
            ->groupBy(
                'promo_bundles.id',
                'promo_bundles.nama_bundel',
                'promo_bundles.deskripsi',
                'promo_bundles.mulai_berlaku',
                'promo_bundles.selesai_berlaku',
                'promo_bundles.kode_barcode',
                'promo_bundles.harga_asli',
                'promo_bundles.harga_promo',
                'promo_bundles.created_at'
            );
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('promoBundle-table')
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
            Column::make('nama_bundel'),
            Column::make('deskripsi'),
            Column::make('beli_produk'),
            Column::make('gratis_produk'),
            Column::make('mulai_berlaku'),
            Column::make('selesai_berlaku'),
            Column::make('kode_barcode'),
            Column::make('harga_asli'),
            Column::make('harga_promo'),
            Column::make('created_at'),
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
        return 'PromoBundle_' . date('YmdHis');
    }
}
