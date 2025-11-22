<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PengerajinController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\KategoriProdukController;
use App\Http\Controllers\Admin\FotoProdukController;
use App\Http\Controllers\Admin\UsahaController;
use App\Http\Controllers\Admin\JenisUsahaController;
use App\Http\Controllers\Admin\UsahaPengerajinController;
use App\Http\Controllers\Admin\UsahaJenisController;
use App\Http\Controllers\Admin\UsahaProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\Admin\LaporanController;

use App\Http\Controllers\Guest\PageController;
use App\Http\Controllers\Guest\GuestProductController;

use App\Http\Controllers\ProdukLikeController;
use App\Http\Controllers\ProdukViewController;
use App\Http\Controllers\TransaksiController as ControllersTransaksiController;

// ====================================================
// AUTH
// ====================================================

Route::get('login', [AuthController::class, 'showLoginForm'])->name('loginForm');
Route::post('login', [AuthController::class, 'login'])->name('login');

// ====================================================
// GUEST ROUTES
// ====================================================

Route::get('/', [PageController::class, 'index'])->name('guest-index');
Route::get('/about', [PageController::class, 'about'])->name('guest-about');
Route::get('/contact', [PageController::class, 'contact'])->name('guest-contact');
Route::get('/products', [PageController::class, 'products'])->name('guest-products');
Route::get('/produk/kategori/{slug}', [PageController::class, 'productsByCategory'])->name('guest-productsByCategory');
Route::get('/produk/{id}', [PageController::class, 'singleProduct'])
    ->name('guest-singleProduct');

// ====================================================
// LIKE & VIEW (Guest)
// ====================================================

Route::post('/produk/{id}/view', [ProdukViewController::class, 'store'])->name('produk.view');
Route::post('/produk/{id}/like', [ProdukLikeController::class, 'toggleLike'])->name('produk.like');

// ====================================================
// ADMIN ROUTES
// ====================================================

Route::middleware(['role:admin'])->group(function () {

    // Logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Profile
    Route::get('admin/profile', [AuthController::class, 'profile'])->name('profile');
    Route::get('admin/change-password', [AuthController::class, 'changePassword'])->name('change-password');
    Route::post('update-password', [AuthController::class, 'updatePassword'])->name('update-password');

    // Dashboard
    Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Transaksi
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');

    // CRUD Pengerajin
    Route::resource('admin/pengerajin', PengerajinController::class);

    // CRUD Usaha
    Route::resource('admin/usaha', UsahaController::class);

    // CRUD Jenis Usaha
    Route::resource('admin/jenis-usaha', JenisUsahaController::class);

    // CRUD Kategori Produk
    Route::resource('admin/kategori-produk', KategoriProdukController::class);

    // CRUD Produk
    Route::resource('admin/produk', ProdukController::class);

    // CRUD Foto Produk
    Route::resource('admin/foto-produk', FotoProdukController::class);

    // CRUD Usaha Pengerajin
    Route::resource('admin/usaha-pengerajin', UsahaPengerajinController::class);

    // CRUD Usaha Jenis
    Route::resource('admin/usaha-jenis', UsahaJenisController::class);

    // CRUD Usaha Produk
    Route::resource('admin/usaha-produk', UsahaProdukController::class);

    // ====================================================
    // LAPORAN
    // ====================================================
    Route::prefix('admin/laporan')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('admin.laporan.index');
        Route::get('/transaksi', [LaporanController::class, 'transaksi'])->name('admin.laporan.transaksi');
        Route::get('/pendapatan-usaha', [LaporanController::class, 'pendapatanUsaha'])->name('admin.laporan.pendapatanUsaha');
        Route::get('/produk-terlaris', [LaporanController::class, 'produkTerlaris'])->name('admin.laporan.produkTerlaris');
        Route::get('/produk-slow-moving', [LaporanController::class, 'produkSlowMoving'])->name('admin.laporan.produkSlowMoving');
        Route::get('/transaksi-user', [LaporanController::class, 'transaksiUser'])->name('admin.laporan.transaksiUser');
        Route::get('/kategori-produk', [LaporanController::class, 'kategoriProduk'])->name('admin.laporan.kategoriProduk');

        // Produk views report
        Route::get('/produk-views', [ProdukController::class, 'produkViews'])
            ->name('admin.laporan.produkViews');
    });

});
