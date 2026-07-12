<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ComponentController;

use App\Http\Controllers\ImportController;
use App\Http\Controllers\ExportController;


Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login',[AuthController::class,'showLogin']);

Route::post('/login',[AuthController::class,'login']);

Route::post('/logout',[AuthController::class,'logout']);

Route::get('/dashboard',function(){

    return "Đăng nhập thành công";

})->middleware('auth');

// Đăng nhập
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Đăng xuất (nên dùng POST)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard (yêu cầu đăng nhập)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');



    

Route::middleware('auth')->group(function () {

    Route::resource('categories', CategoryController::class);
    Route::resource('components', ComponentController::class);



// Hiển thị form nhập kho
Route::get('/imports/create', [ImportController::class, 'create'])->name('imports.create');

// Xử lý lưu dữ liệu từ form gửi lên
Route::post('/imports/store', [ImportController::class, 'store'])->name('imports.store');
});

// === CÁC ĐƯỜNG DẪN CHO QUẢN LÝ NHẬP KHO ===
// 1. Trang danh sách phiếu nhập
Route::get('/imports', [ImportController::class, 'index'])->name('imports.index');

// 2. Trang form thêm phiếu nhập mới
Route::get('/imports/create', [ImportController::class, 'create'])->name('imports.create');

// 3. Xử lý lưu form
Route::post('/imports', [ImportController::class, 'store'])->name('imports.store');

// 4. Trang xem chi tiết phiếu nhập
Route::get('/imports/{id}', [ImportController::class, 'show'])->name('imports.show');

// === CÁC ĐƯỜNG DẪN CHO QUẢN LÝ XUẤT KHO ===
Route::get('/exports', [ExportController::class, 'index'])->name('exports.index');
Route::get('/exports/create', [ExportController::class, 'create'])->name('exports.create');
Route::post('/exports', [ExportController::class, 'store'])->name('exports.store');
Route::get('/exports/{id}', [ExportController::class, 'show'])->name('exports.show');