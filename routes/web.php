<?php
use App\Http\Controllers\Web\RegisterController;
use App\Http\Controllers\Web\HomeController;

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

Route::get('/', [HomeController::class, 'index'])->name('web.home');

Route::group(['prefix' => '/register'], function () {
    Route::get('/', [RegisterController::class, 'showFormRegister']);
    Route::post('/', [RegisterController::class, 'register'])->name('web.register');
    Route::get('/thanks', [RegisterController::class, 'showRegisterSuccess']);

    Route::get('/verify/{id}', [RegisterController::class, 'verify'])
        ->name('web.register.verify')
        ->middleware('signed');
    Route::get('/resend', [RegisterController::class, 'showFormVerification']);
    Route::post('/resend', [RegisterController::class, 'resendVerificationLink'])
        ->name('web.register.resend_verify_link');
});

Route::post('/login', function () {
    // TODO: login route
})->name('web.login');
