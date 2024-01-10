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
use App\Models\LaporanProducts;
use App\Models\Product;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        return view('pages.laporan.index');
    }

    public function laporansTable(LaporanDataTable $dataTable, Request $request){
        $shift = $request->input('shift');

        return $dataTable->with([
            'shift' => $shift
        ])->ajax();
    }

    public function returnProductDataTable()
    {

        $model = ReturnProduct::query()
            ->join('laporans', 'return_products.laporan_id', '=', 'laporans.id')
            ->join('products', 'return_products.product_id', '=', 'products.id')
            ->select([
                'return_products.*',
                'laporans.no_laporan',
                'products.nama_produk',
            ]);

        return DataTables::of($model)
            ->editColumn('no_laporan', function (ReturnProduct $model) {
                return $model->laporan->no_laporan;
            })
            ->editColumn('nama_produk', function (ReturnProduct $model) {
                return $model->product->nama_produk;
            })
            ->editColumn('jumlah', function (ReturnProduct $model) {
                return $model->jumlah;
            })
            ->editColumn('satuan', function (ReturnProduct $model) {
                return $model->satuan;
            })
            ->editColumn('deskripsi', function (ReturnProduct $model) {
                return $model->deskripsi;
            })
            ->editColumn('created_at', function (ReturnProduct $model) {
                return Carbon::parse($model->created_at)->format('d-M-Y H:i');
            })
            // ->addColumn('action', 'returnproduct.action')
            // ->rawColumns(['action'])
            ->make(true);
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

    public function returnProduct(Request $request)
    {
        $req = $request->validate([
            'laporan_id' => ['required'],
            'laporan_product_id' => ['required'],
            'product_id' => ['required'],
            'jumlah' => ['required'],
            'satuan' => ['required'],
            'deskripsi' => ['required'],
        ]);


        $laporanProducts = LaporanProducts::find($req['laporan_product_id']);
        $product = Product::find($req['product_id']);
        $return = ReturnProduct::where('laporan_product_id', $req['laporan_product_id'])->first();

        $laporanProducts->update([
            'jumlah' => $laporanProducts->jumlah - convertUnit($laporanProducts->satuan, $req['satuan'], $req['jumlah'])
        ]);

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

    public function pdf($no_lapran){
        $laporan = Laporan::where('no_laporan', $no_lapran)->first();
        

        $pdf = Pdf::loadView('pages.laporan.pdf', [
            'laporan' => $laporan
        ])->setPaper('a4', 'potrait');
        return $pdf->download('Laporan_' . $laporan->no_laporan . '.pdf');
    }
}
