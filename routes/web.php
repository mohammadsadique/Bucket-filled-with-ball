<?php

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

use App\Http\Controllers\BucketController;
use App\Http\Controllers\BallController;
use App\Http\Controllers\StoreBallInBucketController;

Route::get('/', [BucketController::class ,'home'])->name('home');
Route::post('/buckets', [BucketController::class , 'store'])->name('buckets_store');
Route::post('/ball', [BallController::class , 'store'])->name('balls_store');
Route::post('/fill_bucket', [StoreBallInBucketController::class , 'fill_bucket'])->name('fill_bucket');
