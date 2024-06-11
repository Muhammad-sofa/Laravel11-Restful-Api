<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::post('/users', [\App\Http\Controllers\UserController::class, 'register']);
