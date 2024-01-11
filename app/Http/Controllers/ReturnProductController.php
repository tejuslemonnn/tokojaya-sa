<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\DataTables\LaporanProductsDataTable;
use App\Models\LaporanProducts;

class ReturnProductController extends Controller
{
    public function showReturn(Request $request)
    {
        $laporan = Laporan::where('no_laporan', $request->noStruk)->first();

        $datatable = DataTables::eloquent(LaporanProducts::query()->join('products', 'laporan_products.product_id', '=', 'products.id')
            ->leftJoin('return_products', 'laporan_products.id', '=', 'return_products.laporan_product_id')
            ->where('laporan_products.laporan_id', $laporan->id)
            ->select([
                'laporan_products.*',
                'products.nama_produk',
                'return_products.jumlah as return_jumlah, return_products.satuan as return_satuan',
            ]))
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
            ->editColumn('action', function (LaporanProducts $item){
                return '<button class="'. 'btn btn-sm btn-success "'  . '>Return</button>';
            })
            ->toJson();

        return response()->json([
            'laporan' => $laporan,
            'datatable' => $datatable
        ]);
    }
}
