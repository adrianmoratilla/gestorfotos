<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PictureController;

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