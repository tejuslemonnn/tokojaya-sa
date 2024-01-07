<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use App\Models\Product;
use App\Models\Category;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\DataTables\ProductsDataTable;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function upload($folder = 'produk', $key = 'foto', $validation = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|sometimes')
    {
        request()->validate([$key => $validation]);

        $file = null;
        if (request()->hasFile($key)) {
            $file = Storage::disk('public')->putFile($folder, request()->file($key), 'public');
        }

        return $file;
    }

    public function index(ProductsDataTable $dataTable)
    {
        return $dataTable->render('pages.products.index');
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        File::delete(public_path('storage/' . $product->foto));
        $product->delete();

        return redirect()->intended('products')->with('success', 'Berhasil Menghapus!');
    }

    public function create()
    {
        return view('pages.products.create', [
            'categories' => Category::all(),
            'satuans' => Satuan::all()
        ]);
    }

    public function store(Request $request)
    {
        $requestData = $request->validate([
            'kode'          => ['required'],
            'nama_produk'   => ['required'],
            'kategori_id'      => ['required'],
            'satuan_id'      => ['required'],
            'harga'         => ['required'],
            'stok'          => ['required'],
            'diskon'          => ['required'],
        ]);

        if ($request->hasFile('foto')) {
            $requestData['foto'] = $this->upload();
        }

        Product::create($requestData);

        return redirect()->intended('products')->with('success', 'Berhasil Menambahkan!');
    }

    public function show($id)
    {
        return view('pages.products.detail', [
            'product' => Product::with('category')->find($id),
            'satuans' => Satuan::all()
        ]);
    }

    public function edit($id)
    {
        return view('pages.products.edit', [
            'product' => Product::with('category')->find($id),
            'categories' => Category::all(),
            'satuans' => Satuan::all()
        ]);
    }

    public function update($id, Request $request)
    {
        $product = Product::find($id);

        $requestData = $request->all();

        if ($request->hasFile('foto')) {
            File::delete(public_path('storage/' . $product->foto));
            $requestData['foto'] = $this->upload();
        }

        $product->update($requestData);

        return redirect()->intended('products')->with('success', 'Berhasil Mengupdate!');
    }

    public function cetakBarcode(Request $request)
    {
        if (empty($request->id_product) || $request->id_product == []) {
            return redirect()->back()->with('error', 'Pilih produk dahulu!');
        }

        $products = array();
        foreach ($request->id_product as $id) {
            $product = Product::find($id);
            $products[] = $product;
        }

        $no  = 1;
        $pdf = app()->make(PDF::class);
        $pdf->loadView('pages.products.barcode', compact('products', 'no'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->download('produk.pdf');
    }
}
