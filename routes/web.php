<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - E-Marketplace Jasa Make-Up & Dekorasi Pernikahan
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

// Customer Protected Routes (Shopping Cart, Checkout, Profile, Chat, Orders)
Route::middleware(['auth', 'role:customer,admin'])->group(function () {
    // Shopping Cart & Multi-Item Checkout (Requirement 2)
    Route::get('/keranjang', [CartController::class, 'index'])->name('customer.cart');
    Route::post('/keranjang/tambah', [CartController::class, 'add'])->name('customer.cart.add');
    Route::put('/keranjang/update/{id}', [CartController::class, 'update'])->name('customer.cart.update');
    Route::delete('/keranjang/hapus/{id}', [CartController::class, 'remove'])->name('customer.cart.remove');
    Route::delete('/keranjang/kosongkan', [CartController::class, 'clear'])->name('customer.cart.clear');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('customer.cart.checkout');
    Route::post('/checkout/proses', [CartController::class, 'processCheckout'])->name('customer.cart.checkout.process');

    // Customer Single Order Routes (Backward Compatible)
    Route::post('/pesan/{bundlingId}', [CustomerController::class, 'createOrder'])->name('customer.order.create');
    Route::post('/pesan-layanan/{serviceId}', [CustomerController::class, 'createServiceOrder'])->name('customer.service.order.create');
    Route::get('/pesanan-saya', [CustomerController::class, 'orders'])->name('customer.orders');
    Route::get('/pesanan-saya/{id}', [CustomerController::class, 'orderDetail'])->name('customer.order.detail');
    Route::post('/pesanan-saya/{id}/pembayaran', [CustomerController::class, 'uploadPayment'])->name('customer.order.payment');
    Route::post('/pesanan-saya/{id}/ulasan', [CustomerController::class, 'submitReview'])->name('customer.order.review');

    // Customer Profile Management (Requirement 4)
    Route::get('/profil-saya', [CustomerController::class, 'profile'])->name('customer.profile');
    Route::post('/profil-saya', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');
});

// Chat Communication Routes (Accessible by all authenticated users: Customer, Vendor, Admin)
Route::middleware(['auth'])->group(function () {
    Route::get('/pesan-chat', [ChatController::class, 'customerIndex'])->name('customer.chat');
    Route::get('/pesan-chat/mulai/{vendorId}', [ChatController::class, 'customerStart'])->name('customer.chat.start');
    Route::post('/pesan-chat/kirim/{conversationId}', [ChatController::class, 'customerSendMessage'])->name('customer.chat.send');
    Route::get('/pesan-chat/messages/{conversationId}', [ChatController::class, 'customerGetMessages'])->name('customer.chat.messages');
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

    // Vendor Orders Separation (Requirement 5)
    Route::get('/pesanan', [VendorController::class, 'orders'])->name('orders');
    Route::get('/riwayat-pesanan', [VendorController::class, 'ordersHistory'])->name('orders.history');
    Route::put('/pesanan/{id}/status', [VendorController::class, 'updateOrderStatus'])->name('order.status');

    // Vendor Chat with Customer (Requirement 3)
    Route::get('/chat', [ChatController::class, 'vendorIndex'])->name('chat');
    Route::post('/chat/kirim/{conversationId}', [ChatController::class, 'vendorSendMessage'])->name('chat.send');
    Route::get('/chat/messages/{conversationId}', [ChatController::class, 'vendorGetMessages'])->name('chat.messages');
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

    // Admin Users Management (Requirement 7)
    Route::get('/pengguna', [AdminController::class, 'users'])->name('users');
    Route::put('/pengguna/{id}', [AdminController::class, 'updateUser'])->name('user.update');
    Route::post('/pengguna/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('user.toggle-status');
    Route::delete('/pengguna/{id}', [AdminController::class, 'deleteUser'])->name('user.delete');

    // Admin Vendors Management (Requirement 7)
    Route::get('/vendor', [AdminController::class, 'vendors'])->name('vendors');
    Route::put('/vendor/{id}', [AdminController::class, 'updateVendor'])->name('vendor.update');
    Route::delete('/vendor/{id}', [AdminController::class, 'deleteVendor'])->name('vendor.delete');

    // Admin Chat Monitoring (Requirement 3)
    Route::get('/chat-monitoring', [ChatController::class, 'adminIndex'])->name('chat.monitoring');
    Route::get('/chat-monitoring/{id}', [ChatController::class, 'adminView'])->name('chat.detail');
});
