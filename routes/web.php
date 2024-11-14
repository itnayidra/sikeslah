<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataAreaController;
use App\Http\Controllers\LandSuitabilityController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;

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


// Route akses tamu
Route::get('/', [HomeController::class, 'beranda'])->name('home');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login-proses', [LoginController::class, 'login_proses'])->name('login_proses');
Route::get('/forgot-password', [LoginController::class, 'forgot_password'])->name('forgot-password');
Route::post('/forgot-password-act', [LoginController::class, 'forgot_password_act'])->name('forgot-password-act');
Route::get('/validasi-forgot-password', [LoginController::class, 'validasi_forgot_password'])->name('validasi-forgot-password');
Route::post('/validasi-forgot-password-act', [LoginController::class, 'validasi_forgot_password_act'])->name('validasi-forgot-password-act');
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register-proses', [RegisterController::class, 'register_proses'])->name('register_proses');
Route::get('/layanan/peta-lbs', [HomeController::class, 'layananlbsmap'])->name('lbsmap');
Route::get('/layanan/peta-kesesuaian-lahan', [HomeController::class, 'layanankeslahmap'])->name('keslahmap');
Route::get('/petunjuk-penggunaan', [HomeController::class, 'panduan'])->name('panduan');
Route::get('/informasi-kesesuaian-lahan', [HomeController::class, 'info'])->name('info');
Route::get('/unduh-geojson', [DataAreaController::class, 'unduhGeojson'])->name('unduh.geojson');

// Example route for a download function
// Route::get('/download-geojson', [LandSuitabilityController::class, 'downloadGeoJSON'])->name('download.geojson');

// Route::get('/home', function () {
//     return redirect('/admin/dashboard');
// });

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/store-area', [DataAreaController::class, 'store_area'])->name('store_area');
    Route::delete('/data/{id}', [DataAreaController::class, 'delete'])->name('data.delete');
});

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware(('userAccess:admin'));
Route::get('/create', [AdminController::class, 'create'])->name('admin.create')->middleware(('userAccess:admin'));
Route::post('/store', [AdminController::class, 'store'])->name('admin.store')->middleware(('userAccess:admin'));
Route::get('/edit/{id}', [AdminController::class, 'edit'])->name('admin.user.edit')->middleware(('userAccess:admin'));
Route::put('/update/{id}', [AdminController::class, 'update'])->name('admin.user.update')->middleware(('userAccess:admin'));
Route::delete('/delete/{id}', [AdminController::class, 'delete'])->name('admin.user.delete')->middleware(('userAccess:admin'));
Route::get('/admin/layanan/peta-lbs', [AdminController::class, 'showLbsmap'])->name('admin.lbsmap')->middleware(('userAccess:admin'));
Route::get('/admin/layanan/peta-kesesuaian-lahan', [AdminController::class, 'showKeslahmap'])->name('admin.keslahmap')->middleware(('userAccess:admin'));
Route::get('/admin/layanan/evaluasi-kesesuaian-lahan', [DataAreaController::class, 'showAddAreaForm'])->name('admin.add-area')->middleware(('userAccess:admin'));
Route::get('/admin/layanan/data/{id}/edit', [DataAreaController::class, 'showEditAddAreaForm'])->name('admin.edit_addarea')->middleware(('userAccess:admin'));
Route::put('/admin/layanan/data/{id}/update', [DataAreaController::class, 'updateAddArea'])->name('admin.updateaddarea')->middleware(('userAccess:admin'));
Route::get('/admin/petunjuk-penggunaan', [AdminController::class, 'panduan'])->name('admin.panduan')->middleware(('userAccess:admin'));
Route::get('/admin/info-kesesuaian-lahan', [AdminController::class, 'info'])->name('admin.info');


Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard')->middleware(('userAccess:user'));
Route::get('/user/layanan/peta-lbs', [UserController::class, 'showLbsmap'])->name('user.lbsmap')->middleware(('userAccess:user'));
Route::get('/user/layanan/peta-kesesuaian-lahan', [UserController::class, 'showKeslahmap'])->name('user.keslahmap')->middleware(('userAccess:user'));
Route::get('/user/layanan/evaluasi-kesesuaian-lahan', [DataAreaController::class, 'showAddAreaForm'])->name('user.add-area')->middleware(('userAccess:user'));
Route::get('/user/layanan/data/{id}/edit', [DataAreaController::class, 'showEditAddAreaForm'])->name('user.edit_addarea')->middleware(('userAccess:user'));
Route::put('/user/layanan/data/{id}/update', [DataAreaController::class, 'updateAddArea'])->name('user.updateaddarea')->middleware(('userAccess:user'));
Route::get('/user/petunjuk-penggunaan', [UserController::class, 'panduan'])->name('user.panduan')->middleware(('userAccess:user'));
Route::get('/user/info-kesesuaian-lahan', [UserController::class, 'info'])->name('user.info');

// Route::get('/user/layanan/evaluasi-kesesuaian-lahan', [DataAreaController::class, 'showEditAddAreaForm'])->name('user.edit_addarea')->middleware(('userAccess:user'));

// Route::get('/', function () {
//     return view('home');
// });
// Route::get('/Beranda', function () {
//     return view('Beranda');
// });
// Route::get('/Layanan', function () {
//     return view('layanan');
// });
// Route::get('/Kontak', function () {
//     return view('Kontak');
// });
// Route::get('/masuk', function () {
//     return view('login');
// });
// Route::get('/Hasil Evaluasi', function () {
//     return view('hasilevaluasi');
// });
// Route::post('/store', [DataAreaController::class, 'store'])->name('dataarea.store');

// QUERY
Route::get('/query', [LandSuitabilityController::class, 'query']);
Route::get('/querypilihArea', [LandSuitabilityController::class, 'querypilihArea']);
Route::get('/queryDrawArea', [LandSuitabilityController::class, 'queryDrawArea']);
Route::get('/queryParameter', [LandSuitabilityController::class, 'queryParameter']);
Route::get('/get-location-info', [LandSuitabilityController::class, 'getLocationInfo']);
Route::get('/searchLocation', [LandSuitabilityController::class, 'searchLocation']);
Route::get('/download-geojson', [LandSuitabilityController::class, 'downloadGeoJSON']);
