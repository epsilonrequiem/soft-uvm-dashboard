<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CatalogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
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

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'home']);

	Route::get('/login', [DashboardController::class, 'view']);
	
	Route::get('/dashboard', [DashboardController::class, 'view'])->name('dashboard');
	Route::get('/pruebaciclo', [DashboardController::class, 'pruebaCiclo']);
	Route::get('/leads-ciclo', [DashboardController::class, 'leadsCiclo']);

	Route::get('/usuarios', [UserController::class, 'create'])->name('usuarios');
	Route::get('/usuarios-lista', [UserController::class, 'index']);
	Route::post('/usuario', [UserController::class, 'store']);
	Route::get('/usuario/{id}', [UserController::class, 'show']);
	Route::put('/usuario/{id}', [UserController::class, 'update']);
	Route::put('/usuario-status/{id}', [UserController::class, 'updateEstatus']);

	// Catalogos
	Route::get('/perfiles', [CatalogController::class, 'getPerfiles']);
	Route::get('/campus', [CatalogController::class, 'getCampus']);
	Route::get('/programas', [CatalogController::class, 'getProgramas']);
	Route::get('/paginas/{dominio}', [CatalogController::class, 'getPaginas']);
	Route::get('/dominios', [CatalogController::class, 'getDominios']);
	Route::get('/years', [CatalogController::class, 'getYears']);

	Route::get('/logout', [SessionsController::class, 'destroy']);

	// Route::get('billing', function () {
	// 	return view('billing');
	// })->name('billing');

	// Route::get('profile', function () {
	// 	return view('profile');
	// })->name('profile');

	// Route::get('rtl', function () {
	// 	return view('rtl');
	// })->name('rtl');

	// Route::get('tables', function () {
	// 	return view('tables');
	// })->name('tables');

    // Route::get('virtual-reality', function () {
	// 	return view('virtual-reality');
	// })->name('virtual-reality');

    // Route::get('static-sign-in', function () {
	// 	return view('static-sign-in');
	// })->name('sign-in');

    // Route::get('static-sign-up', function () {
	// 	return view('static-sign-up');
	// })->name('sign-up');

	// Route::get('/user-profile', [InfoUserController::class, 'create']);
	// Route::post('/user-profile', [InfoUserController::class, 'store']);

});

Route::group(['middleware' => 'guest'], function () {
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login', [SessionsController::class, 'create']);
	
	// Route::get('/register', [RegisterController::class, 'create']);
    // Route::post('/register', [RegisterController::class, 'store']);
	// Route::get('/login/forgot-password', [ResetController::class, 'create']);
	// Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	// Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	// Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

Route::get('/login', function () {
    return view('session/login-session');
})->name('login');

