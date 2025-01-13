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
use App\Http\Controllers\HargaBahanController;
use App\Http\Controllers\PrediksiController;
use App\Http\Controllers\PrediksiUserController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;

Route::get('/', [HomeController::class, 'index1'])->middleware('guest')->name('home-user');
Route::get('/bahan-pangan-user', [HargaBahanController::class, 'index'])->middleware('guest')->name('bahan-pangan-user');
Route::get('/prediksi-user', [PrediksiUserController::class, 'form'])->middleware('guest')->name('prediksi-user');
Route::post('/prediksi-user', [PrediksiUserController::class, 'prediksiHarga'])->middleware('guest')->name('harga-prediksi');
Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
Route::group(['middleware' => 'auth'], function () {
	Route::get('/admin', function () {return redirect('/dashboard');});
	Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
	Route::get('/get-chart-data', [HomeController::class, 'getChartData']);
	Route::get('/get-available-years', [HomeController::class, 'getAvailableYears']);
	Route::get('/prediksi', [PrediksiController::class, 'form'])->name('prediksi');
	Route::post('/prediksi', [PrediksiController::class, 'prediksiHarga'])->name('prediksi-harga');
	Route::get('/bahan-pangan', [BahanPanganController::class, 'index'])->name('bahan-pangan');
	Route::post('/bahan-pangan',  [BahanPanganController::class, 'save'])->name('bahan-save');
	Route::post('/bahan-pangan-import',  [BahanPanganController::class, 'importBahanPangan'])->name('bahan-import');
	Route::post('/save-import',  [BahanPanganController::class, 'save_update_pangan'])->name('save-import');
	Route::get('/bahan-pangan/hapus/{id}', [BahanPanganController::class, 'delete'])->name('bahan-hapus');
	Route::get('/bahan-pangan/edit/{id}', [BahanPanganController::class, 'edit'])->name('bahan-edit');
    Route::post('/bahan-pangan/edit/{id}', [BahanPanganController::class, 'update'])->name('bahan-update');
	Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori');
	Route::post('/kategori',  [KategoriController::class, 'save'])->name('kategori-save');
	Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori-create');
	Route::get('/kategori/hapus/{id}', [KategoriController::class, 'delete'])->name('kategori-hapus');
	Route::get('/kategori/edit/{id}', [KategoriController::class, 'edit'])->name('kategori-edit');
    Route::post('/kategori/edit/{id}', [KategoriController::class, 'update'])->name('kategori-update');
	Route::get('/kelola-admin', [UserProfileController::class, 'index'])->name('kelola-admin');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
	Route::get('/download-sample', [BahanPanganController::class, 'downloadSample'])->name('download-sample');
});