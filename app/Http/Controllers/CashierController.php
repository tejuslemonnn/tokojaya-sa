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
use App\Models\LaporanProductReturns;
use App\Models\PromoBundle;
use App\Models\ReturnPenjualan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class CashierController extends Controller
{
    public function index(CashierDataTable $datatable)
    {
        $products = Product::all();
        $cashier = Cashier::where('user_id', auth()->user()->id)->first();
        $promoBundle = PromoBundle::with('promoBundleItems')->get();
        return $datatable->render('pages.cashier.index', compact('products', 'cashier', 'promoBundle'));
    }

    public function cetakStruk(Request $request)
    {
        $return = ReturnPenjualan::where('no_return', $request->no_return)->first();

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
        $latestLaporan = Laporan::latest('no_laporan')->first();
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

        if (!empty($return)) {
            foreach ($return->returnProducts as $value) {
                LaporanProductReturns::create([
                    'laporan_id' => $laporan->id,
                    'product_id' => $value->product_id,
                    'promo_bundle_id' => $value->promo_bundle_id,
                    'jumlah' => $value->jumlah,
                    'satuan' => $value->satuan,
                    'sub_total' => $value->sub_total,
                ]);

                // $product = Product::find($value->product_id);
                // $stok = $product->stok - convertUnit($product->satuan->nama, $value->satuan, $value->jumlah);

                //     $product->update([
                //         'stok' => $stok
                //     ]);
            }
        }


        foreach ($cashier->detail_cashier as $value) {
            LaporanProducts::create([
                'laporan_id' => $laporan->id,
                'product_id' => $value->product_id,
                'promo_bundle_id' => $value->promo_bundle_id,
                'jumlah' => $value->jumlah,
                'satuan' => $value->satuan,
                'sub_total' => $value->sub_total
            ]);

            if ($value->product_id) {
                $product = Product::find($value->product_id);
                $stok = $product->stok - convertUnit($product->satuan->nama, $value->satuan, $value->jumlah);
                $terjual = $product->terjual + convertUnit($product->satuan->nama, $value->satuan, $value->jumlah);

                $product->update([
                    'stok' => $stok,
                    'terjual' => $terjual
                ]);
            } else {
                $promoProducts = $value->promoBundle->promoBundleItems;

                foreach ($promoProducts as $key => $promoProduct) {
                    $stok = $promoProduct->product->stok - convertUnit($promoProduct->product->satuan->nama, $promoProduct->product->satuan->nama, $promoProduct->product->qty);
                    $terjual = $promoProduct->product->terjual + convertUnit($promoProduct->product->satuan->nama, $promoProduct->product->satuan->nama, $promoProduct->qty);

                    $promoProduct->product->update([
                        'stok' => $stok,
                        'terjual' => $terjual
                    ]);
                }
            }
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

        if ($request->tipe === 'product') {
            $product = Product::where('kode', $request->kode)->first();

            if (empty($product)) {
                if ($request->ajax()) {
                    return response()->json(['error' => 'Produk Tidak Ada'], 422);
                } else {
                    return redirect()->back()->with('error', 'Produk Tidak Ada');
                }
            }

            if (!empty($cashier->detail_cashier)) {
                foreach ($cashier->detail_cashier as $key => $value) {
                    if ($cashier->user_id == auth()->user()->id && $value->product && $value->product->kode == $request->kode) {
                        dd($value->product);
                        if ($request->ajax()) {
                            return response()->json(['error' => 'Produk sudah Ada'], 422);
                        } else {
                            return redirect()->back()->with('error', 'Produk sudah Ada');
                        }
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
        } else {
            $promoBundle = PromoBundle::where('kode_barcode', $request->kode_barcode)->first();

            if (empty($promoBundle)) {
                if ($request->ajax()) {
                    return response()->json(['error' => 'Produk Tidak Ada'], 422);
                } else {
                    return redirect()->back()->with('error', 'Produk Tidak Ada');
                }
            }

            if (!empty($cashier->detail_cashier)) {
                foreach ($cashier->detail_cashier as $key => $value) {
                    if ($cashier->user_id == auth()->user()->id && $value->promoBundle && $value->promoBundle->kode_barcode == $request->kode_barcode) {
                        if ($request->ajax()) {
                            return response()->json(['error' => 'Promo Bundle sudah Ada'], 422);
                        } else {
                            return redirect()->back()->with('error', 'Promo Bundle sudah Ada');
                        }
                    }
                }
            }

            $detailCashier = DetailCashier::create([
                'cashier_id' => $cashier->id,
                'promo_bundle_id' => $promoBundle->id,
                'jumlah' => 1,
                'sub_total' => $promoBundle->harga_promo * 1,
                'kategori' => 'promo-bundle',
                'satuan' => 'pcs',
            ]);

            $cashier->update([
                'total_bayar' => $cashier->total_bayar += $detailCashier->sub_total
            ]);
        }



        if ($request->ajax()) {
            return response()->json(['success' => 'Produk berhasil ditambahkan!']);
        } else {
            return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
        }
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
        ]);

        $user = User::role('kepala-kasir')->first();

        if ($user && Hash::check($request->password, $user->password)) {
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

        if ($detailCashier->product) {
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
        } else {
            $detailCashier->update([
                'jumlah' => $request->jumlah,
                'sub_total' => $detailCashier->promoBundle->harga_promo * $request->jumlah
            ]);
        }

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