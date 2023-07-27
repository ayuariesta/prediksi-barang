<?php

use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BahanPanganController;
use App\Http\Controllers\PrediksiController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;

Route::get('/', function () {return redirect('/dashboard');})->middleware('auth');
	Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
	Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
	Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
	Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
	Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
	Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::group(['middleware' => 'auth'], function () {
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::get('/prediksi', [PrediksiController::class, 'form'])->name('prediksi');
	Route::post('/prediksi', [PrediksiController::class, 'prediksiHarga'])->name('prediksi-harga');
	Route::get('/bahan-pangan', [BahanPanganController::class, 'index'])->name('bahan-pangan');
	Route::post('/bahan-pangan',  [BahanPanganController::class, 'save'])->name('bahan-save');
	Route::get('/bahan-pangan/hapus/{id}', [BahanPanganController::class, 'delete'])->name('bahan-hapus');
	Route::get('/bahan-pangan/edit/{id}', [BahanPanganController::class, 'edit'])->name('bahan-edit');
    Route::post('/bahan-pangan/edit/{id}', [BahanPanganController::class, 'update'])->name('bahan-update');
	Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori');
	Route::post('/kategori',  [KategoriController::class, 'save'])->name('kategori-save');
	Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori-create');
	Route::get('/kategori/hapus/{id}', [KategoriController::class, 'delete'])->name('kategori-hapus');
	Route::get('/kategori/edit/{id}', [KategoriController::class, 'edit'])->name('kategori-edit');
    Route::post('/kategori/edit/{id}', [KategoriController::class, 'update'])->name('kategori-update');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});