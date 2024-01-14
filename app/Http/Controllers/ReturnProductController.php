<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use App\Models\Laporan;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ReturnProduct;
use App\Models\LaporanProducts;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\DataTables\LaporanProductsDataTable;

class ReturnProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $satuans = Satuan::all();
        return view('pages.return.index', compact('products', 'satuans'));
    }

    public function showReturn(Request $request)
    {
        $laporan = Laporan::where('no_laporan', $request->noStruk)->first();

        if (empty($laporan)) {
            return response()->json([
                'redirect' => redirect()->back()->with('error', 'Struk Tidak Ada!')->getTargetUrl()
            ], 404);
        }

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
                return 'Rp.' . convertRupiah($item->sub_total);
            })
            ->editColumn('action', function (LaporanProducts $item) {
                return '<button class="btn btn-sm btn-success return-modal-trigger" data-bs-toggle="modal"
                data-bs-target="#returnProductModal" data-satuan="' . $item->satuan . '" data-product-id="' . $item->product_id . '" data-laporan-product-id="' . $item->id . '">Return</button>';
            })
            ->toJson();

        return response()->json([
            'laporan' => $laporan,
            'datatable' => $datatable
        ]);
    }

    public function returnProduct(Request $request)
    {
        $req = $request->validate([
            'laporan_product_id' => ['required'],
            'jumlah' => ['required'],
            'satuan' => ['required'],
            'deskripsi' => ['required'],
        ]);


        $laporanProducts = LaporanProducts::find($req['laporan_product_id']);
        $product = Product::find($laporanProducts->product_id);
        $return = ReturnProduct::where('laporan_product_id', $req['laporan_product_id'])->first();

        $req['laporan_id'] = $laporanProducts->laporan_id;
        $req['product_id'] = $laporanProducts->product_id;

        $product->update([
            'stok' => $product->stok - convertUnit($product->satuan->nama, $req['satuan'], $req['jumlah'])
        ]);

        if (!empty($return)) {
            $return->update([
                'jumlah' => $return->jumlah +  convertUnit($return->satuan, $req['satuan'], $req['jumlah'])
            ]);
        } else {
            ReturnProduct::create($req);
        }


        return redirect()->back()->with('success', 'Berhasil return produk!');
    }
}
