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
        $productsCount = Product::count();
        $totalIncome = Laporan::calculateTotal();
        $totalIncomeToday = Laporan::calculateTotal(today());
        $laporans = Laporan::all();
        $products = Product::orderBy('stok', 'asc')->limit(10)->get();

        return view('pages.dashboard.index', [
            'productCounts' => $productsCount,
            'totalIncome' => $totalIncome,
            'totalIncomeToday' => $totalIncomeToday,
            'laporans' => $laporans,
            'products' => $products
        ]);
    }

    public function incomeStatistics() {
        $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    
        $data = Laporan::all();
    
        $result = [];
    
        foreach ($months as $month) {
            $result[$month] = [
                'total' => 0,
            ];
        }
    
        $groupedData = $data->groupBy(function ($data) {
            return Carbon::parse($data->created_at)->format('Y-m-d');
        });
    
        foreach ($groupedData as $key => $group) {
            $month = Carbon::parse($key)->format('M');
            $result[$month]['total'] = $group->sum('total');
        }
    
        return $result;
    }
    
}
