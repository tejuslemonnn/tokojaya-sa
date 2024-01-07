<?php

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
use App\Http\Controllers\Documentation\ReferencesController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SatuanController;
use App\Models\Laporan;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
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

// Documentations pages
Route::prefix('documentation')->group(function () {
    Route::get('getting-started/references', [ReferencesController::class, 'index']);
    Route::get('getting-started/changelog', [PagesController::class, 'index']);
});

Route::middleware('auth')->group(function () {
    Route::group(['middleware' => ['can:manage shop']], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::resource('laporan', LaporanController::class);
    });

    // Account pages
    Route::prefix('account')->group(function () {
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::put('settings/email', [SettingsController::class, 'changeEmail'])->name('settings.changeEmail');
        Route::put('settings/password', [SettingsController::class, 'changePassword'])->name('settings.changePassword');
    });

    Route::group(['middleware' => ['role:owner']], function () {
        // Logs pages
        Route::prefix('log')->name('log.')->group(function () {
            Route::resource('system', SystemLogsController::class)->only(['index', 'destroy']);
            Route::resource('audit', AuditLogsController::class)->only(['index', 'destroy']);
        });
        Route::resource('users', UsersController::class);
    });


    Route::group(['middleware' => ['can:manage shop']], function () {
        Route::resource('products', ProductsController::class);
        Route::resource('categories', CategoriesController::class);
        Route::resource('satuans', SatuanController::class);
    });

    Route::resource('products', ProductsController::class)->only('index', 'show');
    Route::post('products/cetak-barcode', [ProductsController::class, 'cetakBarcode'])->name('products.cetak-barcode');

    Route::resource('categories', CategoriesController::class)->only('index', 'show');
    Route::resource('satuans', SatuanController::class)->only('index', 'show');

    Route::group(['prefix' => 'cashier'], function () {
        Route::get('/', [CashierController::class, 'index'])->name('cashier');
        Route::post('add-cart', [CashierController::class, 'addCart'])->name('cashier.addCart');
        Route::delete('delete-cart/{id}', [CashierController::class, 'deleteCart'])->name('cashier.deleteCart');
        Route::get('clear-cart', [CashierController::class, 'clearCart'])->name('cashier.clearCart');
        Route::put('update-cart', [CashierController::class, 'updateCart'])->name('cashier.updateCart');
        Route::get('dataProducts', [CashierController::class, 'dataProducts'])->name('cashier.dataProducts');
        Route::post('cetakStruk', [CashierController::class, 'cetakStruk'])->name('cashier.cetakStruk');
        Route::get('change-category', [CashierController::class, 'changeSatuan'])->name('cashier.changeSatuan');
    });

    Route::get('invoice/{id}', [LaporanController::class, 'invoice'])->name('invoice');
    Route::get('returnProductDatatable', [LaporanController::class, 'returnProductDatatable'])->name('returnProductDatatable');
    Route::post('returnProduct', [LaporanController::class, 'returnProduct'])->name('returnProduct');
});


/**
 * Socialite login using Google service
 * https://laravel.com/docs/8.x/socialite
 */
Route::get('/auth/redirect/{provider}', [SocialiteLoginController::class, 'redirect']);

require __DIR__ . '/auth.php';
