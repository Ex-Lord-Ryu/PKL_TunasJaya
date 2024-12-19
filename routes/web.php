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
    Route::get('/user_management', [UserManagementController::class, 'index'])->name('user_management.index')->middleware('superadmin');
    Route::get('/user_management/edit/{id}', [UserManagementController::class, 'edit'])->name('user_management.edit')->middleware('superadmin');
    Route::put('/user_management/update/{id}', [UserManagementController::class, 'update'])->name('user_management.update')->middleware('superadmin');
    Route::delete('/user_management/delete/{id}', [UserManagementController::class, 'destroy'])->name('user_management.delete')->middleware('superadmin');
    Route::post('/user_management/store', [UserManagementController::class, 'store'])->name('user_management.store')->middleware('superadmin');
    Route::get('/user_management/create', [UserManagementController::class, 'create'])->name('user_management.create')->middleware('superadmin');

    //Barang Masuk->Vendor->superadmin, admin
    Route::get('/vendor', [VendorController::class, 'index'])->name('vendor.index');
    Route::get('/vendor/create', [VendorController::class, 'create'])->name('vendor.create')->middleware('admin');
    Route::post('/vendor', [VendorController::class, 'store'])->name('vendor.store');
    Route::get('/vendor/{id}/edit', [VendorController::class, 'edit'])->name('vendor.edit')->middleware('admin');
    Route::put('/vendor/{id}', [VendorController::class, 'update'])->name('vendor.update')->middleware('admin');
    Route::delete('/vendor/{id}', [VendorController::class, 'destroy'])->name('vendor.delete')->middleware('admin');

    //Master Data->Master Motor->superadmin, admin
    Route::get('/master_motor', [MasterMotorController::class, 'index'])->name('master_motor.index');
    Route::get('/master_motor/create', [MasterMotorController::class, 'create'])->name('master_motor.create')->middleware('admin');
    Route::post('/master_motor', [MasterMotorController::class, 'store'])->name('master_motor.store');
    Route::get('/master_motor/{id}/edit', [MasterMotorController::class, 'edit'])->name('master_motor.edit')->middleware('admin');
    Route::put('/master_motor/{id}', [MasterMotorController::class, 'update'])->name('master_motor.update')->middleware('admin');
    Route::delete('/master_motor/{id}', [MasterMotorController::class, 'destroy'])->name('master_motor.delete')->middleware('admin');

    //Master Data->Master Warna->superadmin, admin
    Route::get('/master_warna', [MasterWarnaController::class, 'index'])->name('master_warna.index');
    Route::get('/master_warna/create', [MasterWarnaController::class, 'create'])->name('master_warna.create')->middleware('admin');
    Route::post('/master_warna', [MasterWarnaController::class, 'store'])->name('master_warna.store');
    Route::get('/master_warna/{id_warna}/edit', [MasterWarnaController::class, 'edit'])->name('master_warna.edit')->middleware('admin');
    Route::put('/master_warna/{id_warna}', [MasterWarnaController::class, 'update'])->name('master_warna.update')->middleware('admin');
    Route::delete('/master_warna/{id_warna}', [MasterWarnaController::class, 'destroy'])->name('master_warna.delete')->middleware('admin');

    //Barang Masuk->Pembelian->superadmin, admin
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    Route::get('/pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create')->middleware('admin');
    Route::post('/pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
    Route::get('/pembelian/{id}/edit', [PembelianController::class, 'edit'])->name('pembelian.edit')->middleware('admin');
    Route::put('/pembelian/{id}', [PembelianController::class, 'update'])->name('pembelian.update')->middleware('admin');
    Route::delete('/pembelian/{id}', [PembelianController::class, 'destroy'])->name('pembelian.delete')->middleware('admin');
    Route::get('/pembelian/{id}', [PembelianController::class, 'show'])->name('pembelian.show')->middleware('admin');
    Route::post('/pembelian/update-tanggal/{id}', [PembelianController::class, 'updateTanggal'])->name('pembelian.updateTanggal')->middleware('admin');
    Route::post('/pembelian/{id}/update-status', [PembelianController::class, 'updateStatus'])->name('pembelian.updateStatus')->middleware('admin');

    //Barang Masuk->Pembelian Detail->superadmin, admin
    Route::get('/pembelian_detail', [PembelianDetailController::class, 'index'])->name('pembelian_detail.index');
    Route::get('/pembelian_detail/create', [PembelianDetailController::class, 'create'])->name('pembelian_detail.create')->middleware('admin');
    Route::post('/pembelian_detail', [PembelianDetailController::class, 'store'])->name('pembelian_detail.store');
    Route::get('/pembelian_detail/{id}/edit', [PembelianDetailController::class, 'edit'])->name('pembelian_detail.edit')->middleware('admin');
    Route::put('/pembelian_detail/{id}', [PembelianDetailController::class, 'update'])->name('pembelian_detail.update')->middleware('admin');
    Route::delete('/pembelian_detail/{id}', [PembelianDetailController::class, 'destroy'])->name('pembelian_detail.delete')->middleware('admin');
    Route::get('pembelian_detail/{id}', [PembelianDetailController::class, 'show'])->name('pembelian_detail.show')->middleware('admin');
    Route::post('/pembelian_detail/{id}/update-status', [PembelianDetailController::class, 'updateStatus'])->name('pembelian_detail.updateStatus')->middleware('admin');

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

    //Barang Keluar->Order Motor
    Route::get('/order_motor', [OrderMotorController::class, 'index'])->name('order_motor.index');
    Route::get('/order_motor/create', [OrderMotorController::class, 'create'])->name('order_motor.create')->middleware('user');
    Route::post('/order_motor', [OrderMotorController::class, 'store'])->name('order_motor.store');
    Route::get('/order_motor/{orderMotor}/edit', [OrderMotorController::class, 'edit'])->name('order_motor.edit')->middleware('user');
    Route::put('/order_motor/{orderMotor}', [OrderMotorController::class, 'update'])->name('order_motor.update')->middleware('user');
    Route::delete('/order_motor/{orderMotor}', [OrderMotorController::class, 'destroy'])->name('order_motor.destroy')->middleware('user');
    Route::post('/order-motor/{orderMotor}/cancel', [OrderMotorController::class, 'cancel'])->name('order_motor.cancel')->middleware('user');
    Route::post('/order-motor/{orderMotor}/complete', [OrderMotorController::class, 'complete'])->name('order_motor.complete')->middleware('user');
    Route::get('/order_motor/{orderMotor}', [OrderMotorController::class, 'show'])->name('order_motor.show');

    //Barang Keluar->Sold Motor
    Route::get('/sold_motor', [SoldMotorController::class, 'index'])->name('sold_motor.index');
    Route::get('/sold_motor/{soldMotor}', [SoldMotorController::class, 'show'])->name('sold_motor.show');

    // Routes untuk Reports
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