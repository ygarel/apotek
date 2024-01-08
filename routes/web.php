<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MedicineController;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/login', function(){
//     return view('login');
// })->name('login');
// Route::post('/login',[UserController::class, 'loginAuth'])->name('login.auth');
Route::get('/logout',[UserController::class, 'logout'])->name('logout');

Route::middleware('IsGuest')->group(function () {
    Route::get('/', function() {
        return view('login');
    })->name('login');
    Route::post('/login', [UserController::class, 'loginAuth'])->name('login.auth');
});

Route::get('/error-permission', function() {
    return view('errors.permission');
})->name('error.permission');

Route::middleware(['IsLogin'])->group(function() {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/home', function() {
        return view('home');
    })->name('home.page');
});
    // Route::get('/view', function () {
    //     return view('welcome');
    // })->name('home');

Route::middleware(['IsLogin', 'IsAdmin'])->group(function(){
    //menu data obar
    Route::prefix('/medicine')->name('medicine.')->group(function() {
        Route::get('/create', [MedicineController::class, 'create'])->name('create');
        Route::post('/store', [MedicineController::class, 'store'])->name('store');
        Route::get('/', [MedicineController::class, 'index'])->name('home');
        Route::get('/stock', [MedicineController::class, 'stock'])->name('stock');
        Route::get('/{id}', [MedicineController::class, 'edit'])->name('edit');
        Route::patch('/{id}', [MedicineController::class, 'update'])->name('update');
        Route::delete('/{id}', [MedicineController::class, 'destroy'])->name('delete');
        Route::get('/data/stock/{id}', [MedicineController::class, 'stockEdit'])->name('stock.edit');
        Route::patch('/data/stock/{id}', [MedicineController::class, 'stockUpdate'])->name('stock.update');
    });
    //menu kelola akun
    Route::prefix('/pengguna')->name('pengguna.')->group(function() {
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::get('/', [UserController::class, 'index'])->name('akun');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'edit'])->name('edit');
        Route::patch('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('delete');
    });
    Route::prefix('/order')->name('order.')->group(function(){
        Route::get('/data', [OrderController::class, 'data'])->name('data');
        Route::get('/export-excel', [OrderController::class, 'exportExcel'])->name('export-excel');
    });
});

Route::middleware(['IsLogin', 'IsKasir'])->group(function() {
    Route::prefix('/kasir')->name('kasir.')->group(function() {
        Route::prefix('/order')->name('order.')->group(function() {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/create', [OrderController::class, 'create'])->name('create');
            Route::post('/store', [OrderController::class, 'store'])->name('store');
            Route::get('/print/{id}', [OrderController::class, 'show'])->name('print');
            Route::get('/download/{id}', [OrderController::class, 'downloadPDF'])->name('download');
            Route::get('/search', [OrderController::class, 'search'])->name('search');
        });
    });
});

