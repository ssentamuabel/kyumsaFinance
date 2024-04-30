<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\YearController;
use App\Http\Controllers\UserYearController;
use App\Http\Controllers\ContributionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/auth/register', [UserController::class, 'store']);
Route::post('/auth/login', [UserController::class, 'loginUser']);


Route::post('/year/create', [YearController::class, 'store']);
Route::get('/years', [YearController::class, 'index']);



Route::post('/joint/create', [UserYearController::class, 'store']);
Route::get('/joints', [UserYearController::class, 'index']);



Route::post('/contribution/{id}/create', [ContributionController::class, 'store']);
Route::get('/contributions', [ContributionController::class, 'index']);
Route::get('/user/{id}/contributions', [UserController::class, 'getContributions']);
Route::get('/contributions/notification', [ContributionController::class, 'notification']);

