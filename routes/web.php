<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\SoldMotorController;
use App\Http\Controllers\OrderMotorController;
use App\Http\Controllers\MasterMotorController;
use App\Http\Controllers\MasterWarnaController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\PembelianDetailController;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;
use App\Http\Middleware\superadmin;
use App\Http\Middleware\SalesMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changepassword'])->name('profile.change-password');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::get('/blank-page', [HomeController::class, 'blank'])->name('blank');

    // User Management->superadmin
    Route::prefix('user_management')->middleware('superadmin')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('user_management.index');
        Route::get('/create', [UserManagementController::class, 'create'])->name('user_management.create');
        Route::post('/', [UserManagementController::class, 'store'])->name('user_management.store');
        Route::get('/{id}/edit', [UserManagementController::class, 'edit'])->name('user_management.edit');
        Route::put('/{id}', [UserManagementController::class, 'update'])->name('user_management.update');
        Route::delete('/{id}', [UserManagementController::class, 'destroy'])->name('user_management.destroy');
    });

    //Barang Masuk->Vendor->superadmin, admin, user 
    Route::get('/vendor', [VendorController::class, 'index'])->name('vendor.index')->middleware(UserMiddleware::class);
    Route::get('/vendor/create', [VendorController::class, 'create'])->name('vendor.create')->middleware(AdminMiddleware::class);
    Route::post('/vendor', [VendorController::class, 'store'])->name('vendor.store')->middleware(UserMiddleware::class);
    Route::get('/vendor/{id}/edit', [VendorController::class, 'edit'])->name('vendor.edit')->middleware(UserMiddleware::class);
    Route::put('/vendor/{id}', [VendorController::class, 'update'])->name('vendor.update')->middleware(UserMiddleware::class);
    Route::delete('/vendor/{id}', [VendorController::class, 'destroy'])->name('vendor.delete')->middleware(superadmin::class);

    //Master Data->Master Motor->superadmin, admin
    Route::get('/master_motor', [MasterMotorController::class, 'index'])->name('master_motor.index')->middleware(AdminMiddleware::class);
    Route::get('/master_motor/create', [MasterMotorController::class, 'create'])->name('master_motor.create')->middleware(AdminMiddleware::class);
    Route::post('/master_motor', [MasterMotorController::class, 'store'])->name('master_motor.store')->middleware(AdminMiddleware::class);
    Route::get('/master_motor/{id}/edit', [MasterMotorController::class, 'edit'])->name('master_motor.edit')->middleware(AdminMiddleware::class);
    Route::put('/master_motor/{id}', [MasterMotorController::class, 'update'])->name('master_motor.update')->middleware(AdminMiddleware::class);
    Route::delete('/master_motor/{id}', [MasterMotorController::class, 'destroy'])->name('master_motor.delete')->middleware(superadmin::class);

    //Master Data->Master Warna->superadmin, admin
    Route::get('/master_warna', [MasterWarnaController::class, 'index'])->name('master_warna.index')->middleware(AdminMiddleware::class);
    Route::get('/master_warna/create', [MasterWarnaController::class, 'create'])->name('master_warna.create')->middleware(AdminMiddleware::class);
    Route::post('/master_warna', [MasterWarnaController::class, 'store'])->name('master_warna.store')->middleware(AdminMiddleware::class);
    Route::get('/master_warna/{id_warna}/edit', [MasterWarnaController::class, 'edit'])->name('master_warna.edit')->middleware(AdminMiddleware::class);
    Route::put('/master_warna/{id_warna}', [MasterWarnaController::class, 'update'])->name('master_warna.update')->middleware(AdminMiddleware::class);
    Route::delete('/master_warna/{id_warna}', [MasterWarnaController::class, 'destroy'])->name('master_warna.delete')->middleware(superadmin::class);

    //Barang Masuk->Pembelian->superadmin, admin, user
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index')->middleware(UserMiddleware::class);
    Route::get('/pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create')->middleware(AdminMiddleware::class);
    Route::post('/pembelian', [PembelianController::class, 'store'])->name('pembelian.store')->middleware(UserMiddleware::class);
    Route::get('/pembelian/{id}/edit', [PembelianController::class, 'edit'])->name('pembelian.edit')->middleware(UserMiddleware::class);
    Route::put('/pembelian/{id}', [PembelianController::class, 'update'])->name('pembelian.update')->middleware(UserMiddleware::class);
    Route::delete('/pembelian/{id}', [PembelianController::class, 'destroy'])->name('pembelian.delete')->middleware(superadmin::class);
    Route::get('/pembelian/{id}', [PembelianController::class, 'show'])->name('pembelian.show')->middleware(UserMiddleware::class);
    Route::post('/pembelian/update-tanggal/{id}', [PembelianController::class, 'updateTanggal'])->name('pembelian.updateTanggal')->middleware(UserMiddleware::class);
    Route::post('/pembelian/{id}/update-status', [PembelianController::class, 'updateStatus'])->name('pembelian.updateStatus')->middleware(UserMiddleware::class);

    //Barang Masuk->Pembelian Detail->superadmin, admin, user
    Route::get('/pembelian_detail', [PembelianDetailController::class, 'index'])->name('pembelian_detail.index')->middleware(UserMiddleware::class);
    Route::get('/pembelian_detail/create', [PembelianDetailController::class, 'create'])->name('pembelian_detail.create')->middleware(AdminMiddleware::class);
    Route::post('/pembelian_detail', [PembelianDetailController::class, 'store'])->name('pembelian_detail.store')->middleware(UserMiddleware::class);
    Route::get('/pembelian_detail/{id}/edit', [PembelianDetailController::class, 'edit'])->name('pembelian_detail.edit')->middleware(UserMiddleware::class);
    Route::put('/pembelian_detail/{id}', [PembelianDetailController::class, 'update'])->name('pembelian_detail.update')->middleware(UserMiddleware::class);
    Route::delete('/pembelian_detail/{id}', [PembelianDetailController::class, 'destroy'])->name('pembelian_detail.delete')->middleware(UserMiddleware::class);
    Route::get('pembelian_detail/{id}', [PembelianDetailController::class, 'show'])->name('pembelian_detail.show')->middleware(UserMiddleware::class);
    Route::post('/pembelian_detail/{id}/update-status', [PembelianDetailController::class, 'updateStatus'])->name('pembelian_detail.updateStatus')->middleware(superadmin::class);

    //Barang Keluar->Stock
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/create', [StockController::class, 'create'])->name('stock.create');
    Route::post('/stock', [StockController::class, 'store'])->name('stock.store');
    Route::get('/stock/{id}/edit', [StockController::class, 'edit'])->name('stock.edit');
    Route::put('/stock/{id}', [StockController::class, 'update'])->name('stock.update');
    Route::delete('/stock/{id}', [StockController::class, 'destroy'])->name('stock.delete');
    Route::get('/stock/{stock}', [StockController::class, 'show'])->name('stock.show');
    Route::get('/stock/input-nomor/{invoice}', [StockController::class, 'inputNomor'])->name('stock.inputNomor');
    Route::post('/stock/save-nomor', [StockController::class, 'saveNomor'])->name('stock.saveNomor');
    Route::get('/stock/{id}/edit-pricing', [StockController::class, 'editPricing'])->name('stock.editPricing');
    Route::put('/stock/{id}/update-pricing', [StockController::class, 'updatePricing'])->name('stock.updatePricing');

    //Barang Keluar->Order Motor->superadmin
    Route::get('/order_motor', [OrderMotorController::class, 'index'])->name('order_motor.index');
    Route::get('/order_motor/create', [OrderMotorController::class, 'create'])->name('order_motor.create')->middleware('user');
    Route::post('/order_motor', [OrderMotorController::class, 'store'])->name('order_motor.store')->middleware('user');
    Route::get('/order_motor/{orderMotor}/edit', [OrderMotorController::class, 'edit'])->name('order_motor.edit')->middleware('user');
    Route::put('/order_motor/{orderMotor}', [OrderMotorController::class, 'update'])->name('order_motor.update')->middleware('user');
    Route::delete('/order_motor/{orderMotor}', [OrderMotorController::class, 'destroy'])->name('order_motor.destroy')->middleware('user');
    Route::post('/order-motor/{orderMotor}/cancel', [OrderMotorController::class, 'cancel'])->name('order_motor.cancel')->middleware('user');
    Route::post('/order-motor/{orderMotor}/complete', [OrderMotorController::class, 'complete'])->name('order_motor.complete')->middleware('user');
    Route::get('/order_motor/{orderMotor}', [OrderMotorController::class, 'show'])->name('order_motor.show');

    //Barang Keluar->Sold Motor->superadmin
    Route::get('/sold_motor', [SoldMotorController::class, 'index'])->name('sold_motor.index');
    Route::get('/sold_motor/{soldMotor}', [SoldMotorController::class, 'show'])->name('sold_motor.show');

    //Reports->superadmin, admin, user, sales
    Route::get('report/penjualan', [ReportController::class, 'penjualan'])->name('report.penjualan');
    Route::get('report/pembelian', [ReportController::class, 'pembelian'])->name('report.pembelian');
    Route::get('report/stock', [ReportController::class, 'stock'])->name('report.stock');
    Route::get('report/penjualan/pdf', [ReportController::class, 'exportPenjualanPDF'])->name('report.penjualan.pdf');
    Route::get('report/penjualan/excel', [ReportController::class, 'exportPenjualanExcel'])->name('report.penjualan.excel');
    Route::get('report/pembelian/pdf', [ReportController::class, 'exportPembelianPDF'])->name('report.pembelian.pdf');
    Route::get('report/pembelian/excel', [ReportController::class, 'exportPembelianExcel'])->name('report.pembelian.excel');
    Route::get('report/stock/pdf', [ReportController::class, 'exportStockPDF'])->name('report.stock.pdf');
    Route::get('report/stock/excel', [ReportController::class, 'exportStockExcel'])->name('report.stock.excel');
});