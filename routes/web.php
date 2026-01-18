<?php

use App\Http\Controllers\ViolationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.index');
})->name('home');

Route::get('violations/search-student', [ViolationController::class, 'searchStudent'])->name('violations.searchStudent');
Route::resource('violations', ViolationController::class);