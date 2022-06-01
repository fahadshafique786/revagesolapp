<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::group(
    [
        'middleware' => ['auth'],
        'prefix' => 'admin/',
    ],
    function() {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/fetchusersdata', [UserController::class, 'fetchusersdata']);
        Route::post('/add-update-user', [UserController::class, 'store']);
        Route::post('/edit-user', [UserController::class, 'edit']);
        Route::post('/delete-user', [UserController::class, 'destroy']);
        Route::post('/edit-profile', [UserController::class, 'editProfile']);
        Route::post('/update-profile', [UserController::class, 'updateProfile']);


    });


//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Route::get('admin', function () {
//    return view('admin');
//});




