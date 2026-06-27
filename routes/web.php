<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;

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

    Route::resource('category', CategoryController::class);

});