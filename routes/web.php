<?php

use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('verified')->name('home');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function(){

    Route::group([['middleware' => 'guest:admin']], function(){
        Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showAdminLoginForm'])->name('loginForm');
        Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'adminLogin'])->name('login');
        Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showAdminRegisterForm'])->name('registerForm');
        Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'createAdmin'])->name('register');
    });

    Route::group(['middleware' => 'auth:admin'], function(){
        Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
        Route::get('/', [App\Http\Controllers\HomeController::class, 'adminDashboard'])->name('dashboard');
        Route::get('/users/toggleActive/{user}', [UserController::class, 'toggleActive'])->name('users.toggleActive');
        Route::resource('users', UserController::class);
    });

});

Route::get('/email/verify', function(){
    return view('auth.verify');
})->middleware(['auth'])->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function(EmailVerificationRequest $request){
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');
