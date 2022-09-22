<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PictureController;
use App\Http\Controllers\Auth\ForgotPasswordController;


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

Route::view('/login','users.login')->name('login');
Route::view('/register','users.register')->name('register');
Route::get('/logout',[UserController::class,'logout'])->name('logout');
Route::post('/do-login',[UserController::class,'login'])->name('do-login');
Route::post('/do-register',[UserController::class,'register'])->name('do-register');

Route::middleware('auth')->get('/',[PictureController::class,'home'])->name('home');
Route::middleware('auth')->post('/save-picture',[PictureController::class,'saveAjax'])->name('save-picture');
Route::middleware('auth')->get('/picture/{picture}',[PictureController::class,'getPicture'])->name('get-picture');
Route::middleware('auth')->delete('/remove-picture',[PictureController::class,'removePicture'])->name('remove-picture');
Route::middleware('auth')->get('/filter',[PictureController::class,'filterPictures'])->name('filter-pictures');



Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');
