<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Satuan;
use App\Models\Laporan;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ReturnProduct;
use App\Models\LaporanProducts;
use App\Models\ReturnPenjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\DataTables\ReturnProductDataTable;
use App\Models\PromoBundle;

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
        $return = ReturnPenjualan::where('no_return', $request->noReturn)->first();
        return response()->json([
            'datatable' => $returnProductDataTable->with([
                'no_return' => $request->noReturn
            ])->ajax(),
            'noReturn' => $request->noReturn,
            'return' => $return
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

        $datatable = DataTables::eloquent(LaporanProducts::query()
            ->leftJoin('products', function ($join) use ($laporan) {
                $join->on('laporan_products.product_id', '=', 'products.id');
            })
            ->leftJoin('promo_bundles', function ($join) use ($laporan) {
                $join->on('laporan_products.promo_bundle_id', '=', 'promo_bundles.id')
                    ->whereNull('laporan_products.product_id');
            })
            ->where('laporan_products.laporan_id', $laporan->id)
            ->select([
                'laporan_products.*',
                'products.nama_produk',
                'promo_bundles.nama_bundel',
            ]))
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
                return 'Rp.' . convertRupiah($item->sub_total);
            })
            ->editColumn('qty', function (LaporanProducts $item) {

                if ($item->product_id) {
                    return '<input type="number" name="jumlah[]" class="form-control input-sm quantity" data-item-id="' . $item->id . '" data-kategori="' . $item->product->kategori_id . '" data-harga="' . $item->product->harga . '" value="' . 0 . '" min="0" max="' . $item->jumlah . '" size="3">'
                        . '<input type="hidden" name="product_id[]" value="' . $item->product_id  . '">'
                        . '<input type="hidden" name="satuan[]" value="' . $item->satuan  . '">'
                        . '<input type="hidden" name="tipe[]" value="product">';
                } else {
                    return '<input type="number" name="jumlah[]" class="form-control input-sm quantity" data-item-id="' . $item->id . '" data-harga="' . $item->promoBundle->harga_promo . '" value="' . 0 . '" min="0" max="' . $item->jumlah . '" size="3">'
                        . '<input type="hidden" name="product_id[]" value="' . $item->promo_bundle_id  . '">'
                        . '<input type="hidden" name="tipe[]" value="promoBundle">';
                }
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
            'no_return' =>  $newReturnNo,
            'laporan_id' => $laporan->id,
            'user_id' => auth()->user()->id,
            'total' => 0
        ]);

        $total = 0;

        foreach ($request->product_id as $key => $product_id) {
            if ($request->jumlah[$key] > 0) {
                if ($request->tipe[$key] === 'product') {
                    $product = Product::find($product_id);

                    $reqSatuan = $request->satuan[$key];
                    $productSatuan = $product->satuan->nama;

                    $subTotal = 0;

                    if (strtolower(substr($productSatuan, 0, 1)) == 'k' && strtolower(substr($productSatuan, 1, 2)) == $reqSatuan) {
                        $jumlah = $request->jumlah[$key] / 1000;
                        $subTotal = $product->harga *  $jumlah - ($product->harga *  $jumlah * $product->diskon / 100);
                    } else  if (strtolower(substr($productSatuan, 0, 1)) == 'l' && strtolower(substr($reqSatuan, 1, 2)) == strtolower($productSatuan)) {
                        $jumlah = $request->jumlah[$key] / 1000;
                        $subTotal = $product->harga *  $jumlah - ($product->harga *  $jumlah * $product->diskon / 100);
                    } else {
                        $subTotal = $product->harga *  $request->jumlah[$key] - ($product->harga *  $request->jumlah[$key] * $product->diskon / 100);
                    }

                    ReturnProduct::create([
                        'return_penjualan_id' => $returnPenjualan->id,
                        'product_id' => $product_id,
                        'jumlah' => $request->jumlah[$key],
                        'satuan' => $request->satuan[$key],
                        'sub_total' => $subTotal,
                    ]);

                    // $product->update([
                    //     'stok' => $product->stok - convertUnit($product->satuan->nama, $request->satuan[$key], $request->jumlah[$key]),
                    // ]);

                    $total += $subTotal;
                } else {
                    $promoBundle = PromoBundle::find($product_id);

                    $subTotal = $promoBundle->harga_promo *  $request->jumlah[$key];

                    ReturnProduct::create([
                        'return_penjualan_id' => $returnPenjualan->id,
                        'promo_bundle_id' => $product_id,
                        'jumlah' => $request->jumlah[$key],
                        'satuan' => 'pcs',
                        'sub_total' => $subTotal,
                    ]);

                    // $product->update([
                    //     'stok' => $product->stok - convertUnit($product->satuan->nama, $request->satuan[$key], $request->jumlah[$key]),
                    // ]);

                    $total += $subTotal;
                }
            }
        }

        $returnPenjualan->update([
            'total' => $total
        ]);

        Session::flash('strukUrl', route('invoiceReturn', $returnPenjualan->no_return));

        return redirect()->back()->with('success', 'Return Berhasil!');
    }

    public function invoice($invoiceReturn)
    {
        $return = ReturnPenjualan::with('returnProducts')->where('no_return', $invoiceReturn)->first();

        return view('pages.invoice.invoice_return', ['return' => $return]);
    }

    public function pdfDetail($no_return)
    {
        $return = ReturnPenjualan::where('no_return', $no_return)->first();

        $pdf = Pdf::loadView('pages.return.pdfDetail', [
            'return' => $return
        ])->setPaper('a4', 'potrait');
        return $pdf->download('return_' . $return->no_return . '.pdf');
    }

    public function pdf($shift = null, $fromDate = null, $endDate = null)
    {
        $shift = ($shift == 'semua') ? null : $shift;

        $returns = DB::table('return_penjualans')
            ->leftJoin('users', 'return_penjualans.user_id', '=', 'users.id')
            ->leftJoin('user_infos', 'return_penjualans.user_id', '=', 'user_infos.user_id')
            ->leftJoin('return_products', 'return_penjualans.id', '=', 'return_products.return_penjualan_id')
            ->select(
                'return_penjualans.*',
                'user_infos.shift as shift_kerja',
                'users.name as kasir_name',
                DB::raw('COUNT(return_products.id) as return_products_count')
            )
            ->when(intval($shift), function ($query, $shift) {
                return $query->where('user_infos.shift', $shift);
            })
            ->when($fromDate, function ($query, $fromDate) {
                return $query->whereDate('return_penjualans.created_at', '>=', Carbon::parse($fromDate)->format('Y-m-d H:i:s'));
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('return_penjualans.created_at', '<=', Carbon::parse($endDate)->format('Y-m-d H:i:s'));
            })
            ->groupBy('return_penjualans.id', 'user_infos.shift', 'users.name')
            ->get();

        $pdf = Pdf::loadView('pages.return.pdf', [
            'returns' => $returns
        ])->setPaper('a4', 'potrait');
        return $pdf->download('Returns' . Carbon::now() . '.pdf');
    }

    public function returnProductDataTable(Request $request)
    {

        $model = ReturnPenjualan::query()
            ->leftjoin('laporans', 'return_penjualans.laporan_id', '=', 'laporans.id')
            ->leftJoin('users', 'return_penjualans.user_id', '=', 'users.id')
            ->leftJoin('user_infos', 'return_penjualans.user_id', '=', 'user_infos.user_id')
            ->select([
                'return_penjualans.*',
                'laporans.no_laporan as no_laporan',
                'users.name as nama_kasir',
                'user_infos.shift as shift_kerja'
            ])
            ->when(intval($request->shift), function ($query, $shift) {
                return $query->where('user_infos.shift', $shift);
            })
            ->when($request->from_date, function ($query, $fromDate) {
                return $query->whereDate('return_penjualans.created_at', '>=', Carbon::parse($fromDate)->format('Y-m-d H:i:s'));
            })
            ->when($request->end_date, function ($query, $endDate) {
                return $query->whereDate('return_penjualans.created_at', '<=', Carbon::parse($endDate)->format('Y-m-d H:i:s'));
            });;

        return DataTables::of($model)
            ->editColumn('no_return', function (ReturnPenjualan $model) {
                return $model->no_return;
            })
            ->editColumn('laporan_id', function (ReturnPenjualan $model) {
                return $model->no_laporan;
            })
            ->editColumn('user_id', function (ReturnPenjualan $model) {
                return $model->nama_kasir;
            })
            ->editColumn('shift_kerja', function (ReturnPenjualan $model) {
                return $model->kasir->info->shift;
            })
            ->editColumn('created_at', function (ReturnPenjualan $model) {
                return Carbon::parse($model->created_at)->format('Y-m-d H:i');
            })
            ->addColumn('action', function (ReturnPenjualan $model) {
                return  '<a href="' . route('return.show', $model->no_return) . '" class="btn btn-sm btn-warning my-2 mx-2 btn-active-light">
                Detail
            </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
