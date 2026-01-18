<?php

use App\Http\Controllers\ViolationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});


Route::group(['middleware' => ['auth']], function () {
    //
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('violations/search-student', [ViolationController::class, 'searchStudent'])->name('violations.searchStudent');
    Route::resource('violations', ViolationController::class);
});


Auth::routes(['register' => false, 'reset' => false]);

