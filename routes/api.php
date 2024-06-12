<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::post('/users', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/users/login', [\App\Http\Controllers\UserController::class, 'login']);

Route::middleware(\App\Http\Middleware\ApiAuthMiddleware::class)->group(function () {
     Route::get('/users/current', [\App\Http\Controllers\UserController::class, 'get']);
     Route::patch('/users/current', [\App\Http\Controllers\UserController::class, 'update']);
});