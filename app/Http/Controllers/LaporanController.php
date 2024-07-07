<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use App\Models\Laporan;
use Illuminate\Http\Request;
use App\Models\ReturnProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use App\DataTables\LaporanDataTable;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\DataTables\ReturnProductDataTable;
use App\DataTables\LaporanProductsDataTable;
use App\Models\LaporanProductReturns;
use App\Models\LaporanProducts;
use App\Models\Product;
use App\Models\ReturnPenjualan;
use Carbon\Carbon;
use DB;

class LaporanController extends Controller
{
    public function index()
    {
        return view('pages.laporan.index');
    }

    public function laporansTable(LaporanDataTable $dataTable, Request $request)
    {
        $shift = $request->input('shift');


        return $dataTable->with([
            'shift' => $shift,
            'from_date' => $request->from_date,
            'end_date' => $request->end_date,
        ])->ajax();
    }

    public function show($noLaporan, LaporanProductsDataTable $datatable)
    {
        $laporan = Laporan::where('no_laporan', $noLaporan)->first();
        $satuans = Satuan::all();

        return $datatable->render('pages.laporan.detail', [
            'laporan' => $laporan,
            'satuans' => $satuans
        ]);
    }

    public function destroy($id)
    {
        $laporan = Laporan::find($id);
        $laporan->delete();

        return redirect()->back()->with('success', 'Laporan berhasil dihapus!');
    }

    public function invoice($noLaporan)
    {
        $laporan = Laporan::with('laporan_products')->where('no_laporan', $noLaporan)->first();

        return view('pages.invoice.index', ['laporan' => $laporan]);
    }

    public function pdf($shift = null, $fromDate = null, $endDate = null)
    {
        $shift = ($shift == 'semua') ? null : $shift;

        $laporans = DB::table('laporans')
            ->leftJoin('users', 'laporans.user_id', '=', 'users.id')
            ->leftJoin('user_infos', 'laporans.user_id', '=', 'user_infos.user_id')
            ->select(
                'laporans.*',
                'user_infos.shift as shift_kerja',
                'users.name as kasir_name',
            )
            ->when(intval($shift), function ($query, $shift) {
                return $query->where('user_infos.shift', $shift);
            })
            ->when($fromDate, function ($query, $fromDate) {
                return $query->whereDate('laporans.created_at', '>=', Carbon::parse($fromDate)->format('Y-m-d H:i:s'));
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('laporans.created_at', '<=', Carbon::parse($endDate)->format('Y-m-d H:i:s'));
            })
            ->get();

        $pdf = Pdf::loadView('pages.laporan.pdf', [
            'laporans' => $laporans
        ])->setPaper('a4', 'potrait');
        return $pdf->download('Laporans_' . Carbon::now() . '.pdf');
    }

    public function pdfDetail($no_lapran)
    {
        $laporan = Laporan::where('no_laporan', $no_lapran)->first();


        $pdf = Pdf::loadView('pages.laporan.pdfDetail', [
            'laporan' => $laporan
        ])->setPaper('a4', 'potrait');
        return $pdf->download('Laporan_' . $laporan->no_laporan . '.pdf');
    }

    public function laporanProductReturnsDatatable(Request $request)
    {
        $laporan = Laporan::where('no_laporan', $request->no_laporan)->first();
        $datatable = DataTables::eloquent(LaporanProductReturns::query()
            ->leftJoin('products', function ($join) use ($laporan) {
                $join->on('laporan_product_returns.product_id', '=', 'products.id');
            })
            ->leftJoin('promo_bundles', function ($join) use ($laporan) {
                $join->on('laporan_product_returns.promo_bundle_id', '=', 'promo_bundles.id')
                    ->whereNull('laporan_product_returns.product_id');
            })
            ->where('laporan_product_returns.laporan_id', $laporan->id)
            ->select([
                'laporan_product_returns.*',
                'products.nama_produk',
                'promo_bundles.nama_bundel',
            ]))
            ->editColumn('nama_produk', function (LaporanProductReturns $item) {
                if ($item->product_id) {
                    return $item->product->nama_produk;
                } else {
                    return $item->promoBundle->nama_bundel;
                }
            })
            ->editColumn('jumlah', function (LaporanProductReturns $item) {
                return $item->jumlah;
            })
            ->editColumn('satuan', function (LaporanProductReturns $item) {
                return $item->satuan;
            })
            ->toJson();

        return $datatable;
    }
}
