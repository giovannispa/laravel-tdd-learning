<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/paginate', [UserController::class, 'paginate']);
Route::get('/user/{email}', [UserController::class, 'show']);
Route::put('/users/{email}', [UserController::class, 'update']);
Route::delete('/users/{email}', [UserController::class, 'destroy']);
