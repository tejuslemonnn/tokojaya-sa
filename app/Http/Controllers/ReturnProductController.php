<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class ReturnProductController extends Controller
{
    public function showReturn(Request $request){
        $laporan = Laporan::where('no_laporan', $request->noStruk)->first();
        dd($laporan);
    }
}
