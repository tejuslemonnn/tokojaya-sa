<?php

namespace App\Http\Controllers;

use App\DataTables\SatuanDataTable;
use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index(SatuanDataTable $datatable)
    {
        return $datatable->render('pages.satuans.index');
    }

    public function create()
    {
        return view('pages.satuans.create');
    }

    public function store(Request $request)
    {
        $requestData = $request->validate([
            "nama" => ['required'],
            'kode' => ['required']
        ]);

        Satuan::create($requestData);

        return redirect()->intended('satuans')->with('success', 'Berhasil Menambahkan!');
    }

    public function edit($id)
    {
        return view('pages.satuans.edit', [
            'satuan' => Satuan::find($id)
        ]);
    }

    public function update($id, Request $request)
    {
        $satuan = Satuan::find($id);

        $requestData = $request->all();

        $satuan->update($requestData);

        return redirect()->intended('satuans')->with('success', 'Berhasil Mengupdate!');
    }

    public function destroy($id)
    {
        $satuans = Satuan::find($id);
        $satuans->delete();
        return redirect()->intended('categories')->with('success', 'Berhasil Menghapus!');
    }

    public function show($id)
    {
        return view('pages.satuans.detail', [
            'satuan' => Satuan::find($id),
        ]);
    }
}
