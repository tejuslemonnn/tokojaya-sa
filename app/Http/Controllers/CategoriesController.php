<?php

namespace App\Http\Controllers;

use App\DataTables\CategoryDataTable;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index(CategoryDataTable $datatable)
    {
        return $datatable->render('pages.categories.index');
    }

    public function create()
    {
        return view('pages.categories.create');
    }

    public function store(Request $request)
    {
        $requestData = $request->validate([
            "nama" => ['required'],
            'kode' => ['required']
        ]);

        Category::create($requestData);

        return redirect()->intended('categories')->with('success', 'Berhasil Menambahkan!');
    }

    public function edit($id)
    {
        return view('pages.categories.edit', [
            'category' => Category::find($id)
        ]);
    }

    public function update($id, Request $request)
    {
        $category = Category::find($id);

        $requestData = $request->all();

        $category->update($requestData);

        return redirect()->intended('categories')->with('success', 'Berhasil Mengupdate!');
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();
        return redirect()->intended('categories')->with('success', 'Berhasil Menghapus!');
    }

    public function show($id)
    {
        return view('pages.categories.detail', [
            'category' => Category::find($id),
        ]);
    }
}
