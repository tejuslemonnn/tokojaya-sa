<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use App\Models\Laporan;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ReturnProduct;
use App\Models\LaporanProducts;
use App\Models\ReturnPenjualan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\DataTables\ReturnProductDataTable;

class ReturnProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $satuans = Satuan::all();
        return view('pages.return.index', compact('products', 'satuans'));
    }

    public function show($noReturn, ReturnProductDataTable $datatable)
    {
        $return = ReturnPenjualan::where('no_return', $noReturn)->first();

        return $datatable->render('pages.return.detail', [
            'return' => $return,
        ]);
    }

    public function showReturnDatatable(Request $request, ReturnProductDataTable $returnProductDataTable)
    {
        return response()->json([
            'datatable' => $returnProductDataTable->with([
                'no_return' => $request->noReturn
            ])->ajax()
        ]);
    }

    public function showLaporanDatatable(Request $request)
    {
        $laporan = Laporan::where('no_laporan', $request->noStruk)->first();

        if (empty($laporan)) {
            return response()->json([
                'redirect' => redirect()->back()->with('error', 'Struk Tidak Ada!')->getTargetUrl()
            ], 404);
        }

        $datatable = DataTables::eloquent(LaporanProducts::query()->join('products', 'laporan_products.product_id', '=', 'products.id')
            ->where('laporan_products.laporan_id', $laporan->id)
            ->select([
                'laporan_products.*',
                'products.nama_produk',
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
            ->editColumn('qty', function (LaporanProducts $item) {
                return '<input type="number" name="jumlah[]" class="form-control input-sm quantity" data-item-id="' . $item->id . '" data-kategori="' . $item->product->kategori_id . '" data-harga="' . $item->product->harga . '" value="' . 0 . '" min="0" max="' . $item->jumlah . '" size="3">'
                    . '<input type="hidden" name="product_id[]" value="' . $item->product_id  . '">'
                    . '<input type="hidden" name="satuan[]" value="' . $item->satuan  . '">';
            })
            ->rawColumns(['qty'])
            ->toJson();

        return response()->json([
            'laporan' => $laporan,
            'datatable' => $datatable
        ]);
    }

    public function returnProduct(Request $request)
    {
        $request->validate([
            'no_laporan' => ['required'],
            'jumlah.*' => ['required'],
            'product_id.*' => ['required'],
        ]);

        $laporan = Laporan::with('laporan_products')->where('no_laporan', $request->no_laporan)->first();

        $currentDate = now();
        $datePart = $currentDate->format('Ymd');
        $latestReturn = ReturnPenjualan::latest('no_return')->first();
        $numericPart = $latestReturn ? intval(substr($latestReturn->no_return, -2)) + 1 : 1;
        $numericPartPadded = str_pad($numericPart, 2, '0', STR_PAD_LEFT);
        $newReturnNo = $datePart . $numericPartPadded;

        $returnPenjualan = ReturnPenjualan::create([
            'no_return' => $newReturnNo,
            'laporan_id' => $laporan->id,
            'user_id' => auth()->user()->id,
        ]);

        foreach ($request->product_id as $key => $product_id) {
            if ($request->jumlah[$key] > 1) {
                $product = Product::find($product_id);
                ReturnProduct::create([
                    'return_penjualan_id' => $returnPenjualan->id,
                    'product_id' => $product_id,
                    'jumlah' => $request->jumlah[$key],
                    'satuan' => $request->satuan[$key],
                ]);

                $product->update([
                    'stok' => $product->stok - convertUnit($product->satuan->nama, $laporan->laporan_products[$key]->satuan, $request->jumlah[$key]),
                ]);
            }
        }

        Session::flash('strukUrl', route('invoiceReturn', $returnPenjualan->no_return));

        return redirect()->back()->with('success', 'Return Berhasil!');
    }

    public function invoice($invoiceReturn)
    {
        $return = ReturnPenjualan::with('returnProducts')->where('no_return', $invoiceReturn)->first();

        return view('pages.invoice.invoice_return', ['return' => $return]);
    }
}
