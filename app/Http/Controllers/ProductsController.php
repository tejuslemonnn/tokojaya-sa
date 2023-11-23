<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\DataTables\ProductsDataTable;
use App\Models\Category;
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
        ]);
    }

    public function store(Request $request)
    {
        $requestData = $request->validate([
            'kode'          => ['required'],
            'nama_barang'   => ['required'],
            'kategori_id'      => ['required'],
            'harga'         => ['required'],
            'stok'          => ['required']
        ]);

        $requestData['foto'] = $this->upload();

        Product::create($requestData);

        return redirect()->intended('products')->with('success', 'Berhasil Menambahkan!');
    }

    public function show($id)
    {
        return view('pages.products.detail', [
            'product' => Product::with('category')->find($id),
        ]);
    }

    public function edit($id)
    {
        return view('pages.products.edit', [
            'product' => Product::with('category')->find($id),
            'categories' => Category::all()
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

        return redirect()->intended('products')->with('success', 'Berhasil Update!');
    }
}
