<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - YESI Glam & Decor Marketplace
|--------------------------------------------------------------------------
*/

// Public Marketplace & SPK Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/paket', [HomeController::class, 'bundlings'])->name('bundlings');
Route::get('/paket/{id}', [HomeController::class, 'bundlingDetail'])->name('bundling.detail')->where('id', '[0-9]+');
Route::get('/layanan', [HomeController::class, 'services'])->name('services');
Route::get('/layanan/{id}', [HomeController::class, 'serviceDetail'])->name('service.detail')->where('id', '[0-9]+');
Route::get('/vendor', [HomeController::class, 'vendors'])->name('vendors');
Route::get('/vendor/{id}', [HomeController::class, 'vendorDetail'])->name('vendor.detail')->where('id', '[0-9]+');
Route::get('/rekomendasi-spk', [HomeController::class, 'spkRecommendation'])->name('spk.rekomendasi');

// Midtrans Webhook Callback & Snap Token
Route::post('/midtrans/callback', [MidtransController::class, 'callback'])->name('midtrans.callback');
Route::get('/midtrans/snap-token/{id}', [MidtransController::class, 'getSnapToken'])->name('midtrans.snap-token');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/register-vendor', [AuthController::class, 'showVendorRegisterForm'])->name('register.vendor');
Route::post('/register-vendor', [AuthController::class, 'registerVendor']);
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Customer Protected Routes
Route::middleware(['auth', 'role:customer,admin'])->group(function () {
    Route::post('/pesan/{bundlingId}', [CustomerController::class, 'createOrder'])->name('customer.order.create');
    Route::post('/pesan-layanan/{serviceId}', [CustomerController::class, 'createServiceOrder'])->name('customer.service.order.create');
    Route::get('/pesanan-saya', [CustomerController::class, 'orders'])->name('customer.orders');
    Route::get('/pesanan-saya/{id}', [CustomerController::class, 'orderDetail'])->name('customer.order.detail');
    Route::post('/pesanan-saya/{id}/pembayaran', [CustomerController::class, 'uploadPayment'])->name('customer.order.payment');
    Route::post('/pesanan-saya/{id}/ulasan', [CustomerController::class, 'submitReview'])->name('customer.order.review');
});

// Vendor Protected Routes
Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
    Route::get('/profil', [VendorController::class, 'profile'])->name('profile');
    Route::post('/profil', [VendorController::class, 'updateProfile'])->name('profile.update');
    Route::get('/paket', [VendorController::class, 'bundlings'])->name('bundlings');
    Route::post('/paket', [VendorController::class, 'storeBundling'])->name('bundling.store');
    Route::put('/paket/{id}', [VendorController::class, 'updateBundling'])->name('bundling.update');
    Route::delete('/paket/{id}', [VendorController::class, 'deleteBundling'])->name('bundling.delete');
    Route::get('/galeri', [VendorController::class, 'galleries'])->name('galleries');
    Route::post('/galeri', [VendorController::class, 'storeGallery'])->name('gallery.store');
    Route::delete('/galeri/{id}', [VendorController::class, 'deleteGallery'])->name('gallery.delete');
    Route::get('/pesanan', [VendorController::class, 'orders'])->name('orders');
    Route::put('/pesanan/{id}/status', [VendorController::class, 'updateOrderStatus'])->name('order.status');
});

// Admin Protected Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/kriteria', [AdminController::class, 'criterias'])->name('criterias');
    Route::put('/kriteria/{id}', [AdminController::class, 'updateCriteria'])->name('criteria.update');
    Route::get('/evaluasi', [AdminController::class, 'evaluations'])->name('evaluations');
    Route::post('/evaluasi', [AdminController::class, 'updateEvaluations'])->name('evaluations.update');
    Route::get('/pesanan', [AdminController::class, 'orders'])->name('orders');
    Route::post('/pesanan/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('order.status');
    Route::post('/pembayaran/{id}/verifikasi', [AdminController::class, 'verifyPayment'])->name('payment.verify');
    Route::get('/spk-marcos', [AdminController::class, 'spkMarcos'])->name('spk');
    Route::get('/spk-marcos/print-pdf', [AdminController::class, 'printMarcosPdf'])->name('spk.print-pdf');
    Route::get('/spk-marcos/export-excel', [AdminController::class, 'exportMarcosExcel'])->name('spk.export-excel');
    Route::get('/evaluasi/template-excel', [AdminController::class, 'downloadExcelTemplate'])->name('evaluations.template-excel');
    Route::post('/evaluasi/import-excel', [AdminController::class, 'importExcelEvaluations'])->name('evaluations.import-excel');
    Route::get('/pengguna', [AdminController::class, 'users'])->name('users');
    Route::post('/pengguna/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('user.toggle-status');
    Route::delete('/pengguna/{id}', [AdminController::class, 'deleteUser'])->name('user.delete');
});
