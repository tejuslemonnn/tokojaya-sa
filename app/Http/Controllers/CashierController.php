<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cashier;
use App\Models\Laporan;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\DetailCashier;
use App\Models\LaporanProducts;
use Barryvdh\DomPDF\Facade\Pdf;
use App\DataTables\CashierDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class CashierController extends Controller
{
    public function index(CashierDataTable $datatable)
    {
        $products = Product::all();
        $cashier = Cashier::where('user_id', auth()->user()->id)->first();
        return $datatable->render('pages.cashier.index', compact('products', 'cashier'));
    }

    public function cetakStruk(Request $request)
    {

        if ($request->kembali < 0) {
            return redirect()->back()->with('error', 'Uang yang dibayar kurang');
        }

        $request->validate([
            'bayar' => ['required', 'numeric', 'min:0'],
            'kembali' => ['required', 'numeric', 'min:0'],
        ]);

        $cashier = Cashier::where('user_id', auth()->user()->id)->first();

        $currentDate = now();
        $datePart = $currentDate->format('Ymd');
        $latestLaporan = Laporan::latest()->first();
        $numericPart = $latestLaporan ? intval(substr($latestLaporan->no_laporan, -2)) + 1 : 1;
        $numericPartPadded = str_pad($numericPart, 2, '0', STR_PAD_LEFT);
        $newLaporanNo = $datePart . $numericPartPadded;

        $laporan = Laporan::create([
            'no_laporan' => $newLaporanNo,
            'user_id' => auth()->user()->id,
            'total' => $cashier->total_bayar,
            'bayar' => $request->bayar,
            'kembali' => intval($request->kembali)
        ]);

        foreach ($cashier->detail_cashier as $value) {
            LaporanProducts::create([
                'laporan_id' => $laporan->id,
                'product_id' => $value->product_id,
                'jumlah' => $value->jumlah,
                'satuan' => $value->satuan,
                'sub_total' => $value->sub_total
            ]);

            $product = Product::find($value->product_id);
            $stok = $product->stok - convertUnit($product->satuan->nama, $value->satuan, $value->jumlah);

            $product->update([
                'stok' => $stok
            ]);
        }

        $cashier->delete();

        Session::flash('strukUrl', route('invoice', $laporan->no_laporan));

        return redirect()->back()->with('success', 'Penjualan telah berhasil!');
    }

    public function addCart(Request $request)
    {
        $cashier = Cashier::with('detail_cashier')->where('user_id', auth()->user()->id)->first();

        if (empty($cashier)) {
            $cashier = Cashier::create([
                'user_id' => auth()->user()->id,
                'total_bayar' => 0
            ]);
        }

        $product = Product::where('kode', $request->kode)->first();

        if (empty($product)) {
            return redirect()->back()->with('error', 'Produk Tidak Ada');
        }

        if (!empty($cashier->detail_cashier)) {
            foreach ($cashier->detail_cashier as $key => $value) {
                // ketika sudah ada
                if ($cashier->user_id == auth()->user()->id && $value->product->kode == $request->kode) {
                    return redirect()->back()->with('error', 'Produk sudah Ada');
                }
            }
        }

        $detailCashier = DetailCashier::create([
            'cashier_id' => $cashier->id,
            'product_id' => $product->id,
            'jumlah' => 1,
            'sub_total' => $product->harga * 1 - ($product->harga * 1 * $product->diskon / 100),
            'kategori' => $product->category->nama,
            'satuan' => $product->satuan->nama,
        ]);

        $cashier->update([
            'total_bayar' => $cashier->total_bayar += $detailCashier->sub_total
        ]);

        $cashier->save();

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function deleteCart($id)
    {

        $detailCashier = DetailCashier::find($id);

        $cashier = Cashier::find($detailCashier->cashier_id);
        $cashier->update([
            'total_bayar' => $cashier->total_bayar - $detailCashier->sub_total,
        ]);

        $detailCashier->delete();

        if (count($cashier->detail_cashier) == 0) {
            $cashier->delete();
        }

        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }
    public function clearCart(Request $request)
    {
        $request->validate([
            'password' => ['required'],
            'username' => ['required'],
        ]);

        $user = User::where('username', $request->input('username'))->first();

        if ($user && Hash::check($request->input('password'), $user->password) && $user->hasRole('kepala-kasir')) {
            Cashier::where('user_id', auth()->user()->id)->delete();
            return redirect()->back()->with('success', 'Membatalkan Pesanan!');
        } else {
            $error = $user ? ($user->hasRole('kepala-kasir') ? 'Wajib akun kepala kasir.' : 'Password salah.') : 'Username tidak ditemukan.';
            return redirect()->back()->with('error', $error);
        }
    }
    public function updateCart(Request $request)
    {
        $detailCashier = DetailCashier::find($request->id);

        $subTotal = 0;

        $reqSatuan = $request->satuan;
        $productSatuan = $detailCashier->product->satuan->nama;

        if (strtolower(substr($productSatuan, 0, 1)) == 'k' && strtolower(substr($productSatuan, 1, 2)) == $reqSatuan) {
            $jumlah = $request->jumlah / 1000;
            $subTotal = $detailCashier->product->harga *  $jumlah - ($detailCashier->product->harga *  $jumlah * $detailCashier->product->diskon / 100);
        } else  if (strtolower(substr($productSatuan, 0, 1)) == 'l' && strtolower(substr($reqSatuan, 1, 2)) == strtolower($productSatuan)) {
            $jumlah = $request->jumlah / 1000;
            $subTotal = $detailCashier->product->harga *  $jumlah - ($detailCashier->product->harga *  $jumlah * $detailCashier->product->diskon / 100);
        } else {
            $subTotal = $detailCashier->product->harga *  $request->jumlah - ($detailCashier->product->harga *  $request->jumlah * $detailCashier->product->diskon / 100);
        }

        $detailCashier->update([
            'jumlah' => $request->jumlah,
            'sub_total' => $subTotal
        ]);

        $cashier = Cashier::where('user_id', auth()->user()->id)->first();

        $totalBayar = 0;
        foreach ($cashier->detail_cashier as $key => $value) {
            $totalBayar = $totalBayar += $value->sub_total;
        }

        $cashier->update([
            'total_bayar' => $totalBayar
        ]);

        return response()->json(['detail_cashier' => $detailCashier, 'total' => $totalBayar]);
    }

    public function changeSatuan(Request $request)
    {
        $detailCashier  = DetailCashier::find($request->id);

        $detailCashier->update([
            'satuan' => $request->satuan
        ]);

        return response()->json(['message' => 'Satuan telah diubah!', 'satuan' => $request->satuan]);
    }
}


// public function addCart(Request $request)
//     {
//         $product = Product::where('kode', $request->kode)->first();

        // if (empty($product)) {
        //     return redirect()->back()->with('error', 'Produk Tidak Ada');
        // }

//         $id = $product->id;
//         $cart = session('cart');
//         if (isset($cart[$id])) {
//             $cart[$id]['qty']++;
//             $subTotal = $product->harga * $cart[$id]['qty'];
//             $cart[$id]['subTotal'] = $subTotal - ($subTotal * $cart[$id]['diskon'] / 100);
//         } else {
//             $cart[$id] = [
//                 "id" => $product->id,
//                 "kode" => $product->kode,
//                 "nama_produk" => $product->nama_produk,
//                 "qty" => 1,
//                 "harga" => $product->harga,
//                 "subTotal" => $product->harga - ($product->harga * $product->diskon / 100),
//                 "diskon" => $product->diskon,
//             ];
//         }

//         session()->put('cart', $cart);

//         $totalSession = session('total', 0);
//         $total = 0;
//         foreach ($cart as $item) {
//             if (isset($item['subTotal'])) {
//                 $total += $item['subTotal'];
//             }
//         }

//         session()->put('total', $total);
//         return redirect()->back();
//     }
//     public function deleteCart($id)
//     {
//         $cart = session('cart');
//         $total = session('total');
//         if (isset($cart[$id])) {
//             session()->put('total', $total -= $cart[$id]['subTotal']);
//             unset($cart[$id]);
//             session(['cart' => $cart]);
//         }
//         return redirect()->back();
//     }
//     public function clearCart()
//     {
//         session()->forget('cart');
//         session()->forget('total');
//         return redirect()->back();
//     }
//     public function updateCart(Request $request)
//     {
        // $product = Product::find($request->id);
        // $cart = session('cart');
        // $cart[$request->id]['qty'] = $request->qty;
        // $subTotal = $product->harga * $cart[$request->id]['qty'];
        // $cart[$request->id]['subTotal'] = $subTotal - ($subTotal * $cart[$request->id]['diskon'] / 100);
        // session()->put('cart', $cart);

        // $total = 0;
        // foreach ($cart as $product) {
        //     if (isset($product['subTotal'])) {
        //         $total += $product['subTotal'];
        //     }
        // }

        // session()->put('total', $total);
        // return response()->json(['cart' => $cart, 'total' => $total]);
//     }