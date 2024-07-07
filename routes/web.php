<?php

use App\DataTables\ReturnProductDataTable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Logs\AuditLogsController;
use App\Http\Controllers\Logs\SystemLogsController;
use App\Http\Controllers\Account\SettingsController;
use App\Http\Controllers\Auth\SocialiteLoginController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\PromoBundleController;
use App\Http\Controllers\ReturnProductController;
use App\Http\Controllers\SatuanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you permission register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



$menu = theme()->getMenu();
array_walk($menu, function ($val) {
    if (isset($val['path'])) {
        $route = Route::get($val['path'], [PagesController::class, 'index']);

        // Exclude documentation from auth middleware
        if (!Str::contains($val['path'], 'documentation')) {
            $route->middleware('auth');
        }
    }
});

Route::middleware('auth')->group(function () {
    Route::group(['middleware' => ['permission:manage shop|manage account']], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::resource('laporan', LaporanController::class);
        Route::get('laporan-table', [LaporanController::class, 'laporansTable'])->name('laporan.table');
        Route::get('laporanProductReturnsDatatable', [LaporanController::class, 'laporanProductReturnsDatatable'])->name('laporanProductReturnsDatatable');
        Route::get('pdf-laporan/${no_laporan}', [LaporanController::class, 'pdfDetail'])->name('laporan.pdfDetail');
        Route::get('pdf-laporan-all/{shift?}/{from_date?}/{end_date?}', [LaporanController::class, 'pdf'])->name('laporan.pdf');
        Route::resource('categories', CategoriesController::class);
        Route::resource('satuans', SatuanController::class);
        Route::get('return/{no_laporan}', [ReturnProductController::class, 'show'])->name('return.show');
        Route::get('pdf-return/${no_return}', [ReturnProductController::class, 'pdfDetail'])->name('return.pdfDetail');
        Route::get('pdf-return-all/{shift?}/{from_date?}/{end_date?}', [ReturnProductController::class, 'pdf'])->name('return.pdf');
    });

    // Account pages
    Route::prefix('account')->group(function () {
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::put('settings/email', [SettingsController::class, 'changeEmail'])->name('settings.changeEmail');
        Route::put('settings/password', [SettingsController::class, 'changePassword'])->name('settings.changePassword');
    });

    // Logs pages
    Route::group(['middleware' => ['role:owner']], function () {
        Route::prefix('log')->name('log.')->group(function () {
            Route::resource('system', SystemLogsController::class)->only(['index', 'destroy']);
            Route::resource('audit', AuditLogsController::class)->only(['index', 'destroy']);
        });
        Route::resource('users', UsersController::class);
        Route::get('/users-table', [UsersController::class, 'usersTable'])->name('users.table');
    });


    Route::group(['middleware' => ['permission:manage shop']], function () {
        Route::resource('products', ProductsController::class);
        Route::resource('promo-bundle', PromoBundleController::class);
    });

    Route::resource('products', ProductsController::class)->only('index', 'show');
    Route::resource('promo-bundle', PromoBundleController::class)->only('index', 'show');
    Route::post('products/cetak-barcode', [ProductsController::class, 'cetakBarcode'])->name('products.cetak-barcode');

    Route::group(['middleware' => ['permission:manage sale']], function () {
        Route::group(['prefix' => 'cashier'], function () {
            Route::get('/', [CashierController::class, 'index'])->name('cashier');
            Route::post('add-cart', [CashierController::class, 'addCart'])->name('cashier.addCart');
            Route::delete('delete-cart/{id}', [CashierController::class, 'deleteCart'])->name('cashier.deleteCart');
            Route::delete('clear-cart', [CashierController::class, 'clearCart'])->name('cashier.clearCart');
            Route::put('update-cart', [CashierController::class, 'updateCart'])->name('cashier.updateCart');
            Route::get('dataProducts', [CashierController::class, 'dataProducts'])->name('cashier.dataProducts');
            Route::post('cetakStruk', [CashierController::class, 'cetakStruk'])->name('cashier.cetakStruk');
            Route::get('change-category', [CashierController::class, 'changeSatuan'])->name('cashier.changeSatuan');
        });

        Route::get('showReturnDatatable', [ReturnProductController::class, 'showReturnDatatable'])->name('return.showReturnDatatable');
        Route::get('showLaporanDatatable', [ReturnProductController::class, 'showLaporanDatatable'])->name('return.showLaporanDatatable');
        Route::resource('return', ReturnProductController::class);
        Route::post('returnProduct', [ReturnProductController::class, 'returnProduct'])->name('returnProduct');
    });

    Route::get('invoiceReturn/{no_return}', [ReturnProductController::class, 'invoice'])->name('invoiceReturn');
    Route::get('invoice/{id}', [LaporanController::class, 'invoice'])->name('invoice');
    Route::get('returnProductDatatable', [ReturnProductController::class, 'returnProductDatatable'])->name('returnProductDatatable');
});


Route::get('/auth/redirect/{provider}', [SocialiteLoginController::class, 'redirect']);

require __DIR__ . '/auth.php';
