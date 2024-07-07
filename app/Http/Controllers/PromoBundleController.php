<?php

namespace App\Http\Controllers;

use App\DataTables\PromoBundleDataTable;
use App\Models\Product;
use App\Models\PromoBundle;
use App\Models\PromoBundleItem;
use Illuminate\Http\Request;

class PromoBundleController extends Controller
{
    function index(PromoBundleDataTable $datatable)
    {
        return $datatable->render('pages.promoBundle.index');
    }

    function create()
    {
        $products = Product::with('satuan')->get();

        return view('pages.promoBundle.create', compact('products'));
    }

    function store(Request $request)
    {
        $requestPromoBundle = $request->validate([
            'nama_bundel' => ['required'],
            'deskripsi' => ['nullable'],
            'mulai_berlaku' => ['required'],
            'selesai_berlaku' => ['required'],
            'kode_barcode' => ['required'],
            'harga_asli' => ['required'],
            'harga_promo' => ['required'],
        ]);

        $requestPromoBundleItem = $request->validate([
            'product_id' => ['required'],
            'qty' => ['required'],
            'tipe' => ['required'],
        ]);

        $promoBundle = PromoBundle::create($requestPromoBundle);

        foreach ($requestPromoBundleItem['product_id'] as $key => $value) {
            PromoBundleItem::create([
                'promo_bundle_id' => $promoBundle->id,
                'product_id' => $requestPromoBundleItem['product_id'][$key],
                'qty' => $requestPromoBundleItem['qty'][$key],
                'tipe' => $requestPromoBundleItem['tipe'][$key],
            ]);
        }

        return redirect()->intended('promo-bundle')->with('success', 'Berhasil Menambahkan!');
    }
    public function show($promoBundleId)
    {
        $promoBundle = PromoBundle::find($promoBundleId);
        $products = Product::with('satuan')->get();

        return view('pages.promoBundle.detail', compact('products', 'promoBundle'));
    }

    public function edit($promoBundleId)
    {
        $promoBundle = PromoBundle::find($promoBundleId);
        $products = Product::with('satuan')->get();

        return view('pages.promoBundle.edit', compact('products', 'promoBundle'));
    }

    public function update($promoBundleId, Request $request)
    {
        $requestPromoBundle = $request->validate([
            'nama_bundel' => ['required'],
            'deskripsi' => ['nullable'],
            'mulai_berlaku' => ['required'],
            'selesai_berlaku' => ['required'],
            'kode_barcode' => ['required'],
            'harga_asli' => ['required'],
            'harga_promo' => ['required'],
        ]);

        // Find the promo bundle to update
        $promoBundle = PromoBundle::find($promoBundleId);

        // Update the promo bundle attributes
        $promoBundle->update($requestPromoBundle);

        // Retrieve existing promo bundle items keyed by a combination of product_id and tipe
        $existingItems = PromoBundleItem::where('promo_bundle_id', $promoBundleId)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->product_id . '_' . $item->tipe => $item];
            });

        // Create or update promo bundle items based on request data
        $incomingItems = [];
        foreach ($request->product_id as $index => $productId) {
            $tipe = $request->tipe[$index];
            $key = $productId . '_' . $tipe;
            $incomingItems[] = $key;

            if (isset($existingItems[$key])) {
                // Update existing item
                $existingItem = $existingItems[$key];
                $existingItem->update([
                    'qty' => $request->qty[$index],
                    'tipe' => $tipe,
                ]);
            } else {
                // Create new item
                PromoBundleItem::create([
                    'promo_bundle_id' => $promoBundle->id,
                    'product_id' => $productId,
                    'qty' => $request->qty[$index],
                    'tipe' => $tipe,
                ]);
            }
        }

        // Delete items that are not in the incoming request
        foreach ($existingItems as $key => $existingItem) {
            if (!in_array($key, $incomingItems)) {
                $existingItem->delete();
            }
        }

        return redirect()->intended('promo-bundle')->with('success', 'Berhasil Mengupdate!');
    }

    public function destroy($id)
    {
        $promoBundle = PromoBundle::find($id);
        $promoBundle->delete();

        return redirect()->intended('promo-bundle')->with('success', 'Berhasil Menghapus!');
    }
}
