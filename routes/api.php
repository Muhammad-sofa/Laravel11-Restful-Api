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
     Route::delete('/users/logout', [\App\Http\Controllers\UserController::class, 'logout']);

     Route::post('/contacts', [\App\Http\Controllers\ContactController::class, 'create']);
     Route::post('/contacts', [\App\Http\Controllers\ContactController::class, 'search']);
     Route::get('/contacts/{id}', [\App\Http\Controllers\ContactController::class, 'get'])->where('id', '[0-9]+');
     Route::put('/contacts/{id}', [\App\Http\Controllers\ContactController::class, 'update'])->where('id', '[0-9]+');
     Route::delete('/contacts/{id}', [\App\Http\Controllers\ContactController::class, 'delete'])->where('id', '[0-9]+');

     Route::post('/contacts/{idContact}/addresses', [\App\Http\Controllers\AddressController::class, 'create'])->where('idContact', '[0-9]+');
     Route::get('/contacts/{idContact}/addresses/{idAddress}', [\App\Http\Controllers\AddressController::class, 'get'])->where('idContact', '[0-9]+')->where('idAddress', '[0-9]+');
     Route::put('/contacts/{idContact}/addresses/{idAddress}', [\App\Http\Controllers\AddressController::class, 'update'])->where('idContact', '[0-9]+')->where('idAddress', '[0-9]+');
});