<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Laporan;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index() {
        $products = Product::all();
        $totalIncome = Laporan::calculateTotal();
        $laporans = Laporan::all();

        return view('pages.dashboard.index', [
            'productCounts' => count($products),
            'totalIncome' => $totalIncome,
            'laporans' => $laporans
        ]);
    }

    public function incomeStatistics() {
        $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        $data = Laporan::all();

        $d = $data->groupBy(function ($data) {
            return Carbon::parse($data->created_at)->format('Y-m-d');
        })->map(function ($data) {
            return [
                // 'total'  => 'Rp.' . number_format($data->sum('total'), 0, ',', '.') . '.00',
                'total' => $data->sum('total')
            ];
        })->sortKeys()->mapWithKeys(function ($data, $key) use ($months) {
            return [$months[Carbon::parse($key)->format('n') - 1] => $data];
        });
        

        return $d;
    }
}
