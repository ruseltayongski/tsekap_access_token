<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/invalid/token', [LoginController::class, 'invalidAccessToken'])->name('invalid/token');
Route::get('/expired/token', [UsersController::class, 'expiredAccessToken'])->name('expired/token');
Route::prefix('/user')->group(function(){
    Route::post('/login', [LoginController::class, 'login']);
    Route::middleware(['auth:api','EnsureTokenIsValid'])->get('/profile', [UsersController::class, 'getUserProfile']); //protected API
    Route::middleware(['auth:api','EnsureTokenIsValid'])->get('/profile/info', [UsersController::class, 'getUserInfo']); //protected API
    Route::middleware(['auth:api','EnsureTokenIsValid'])->get('/retrieve', [UsersController::class, 'retrieveUsers']); //protected API
    Route::middleware(['auth:api','EnsureTokenIsValid'])->get('/barangay', [UsersController::class, 'getBarangay']); //protected API
    Route::middleware(['auth:api','EnsureTokenIsValid'])->get('/muncity', [UsersController::class, 'getMunicipality']); //protected API
    Route::middleware(['auth:api','EnsureTokenIsValid'])->get('/user_barangay', [UsersController::class, 'getUserBarangay']); //protected API
    Route::middleware(['auth:api','EnsureTokenIsValid'])->get('/text_blast', [UsersController::class, 'textBlast']); //protected API
});