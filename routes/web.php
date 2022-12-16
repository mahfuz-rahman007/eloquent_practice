<?php

use App\Http\Controllers\EloquentPracticeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::post('user/create' , [HomeController::class, 'userSave'])
->name('userSave');

Route::get('/about' , function(){
    return view('about');
});

Route::get('/practice' , [HomeController::class, 'practice']);

Route::get('/practice2' , [HomeController::class, 'practice2']);

Route::get('/eloquent' , [EloquentPracticeController::class, 'index']);


