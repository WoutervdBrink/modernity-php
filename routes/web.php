<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::get('/searches', [SearchController::class, 'index'])->name('searches.index');
Route::get('/searches/create', [SearchController::class, 'create'])->name('searches.create');
Route::post('/searches', [SearchController::class, 'store'])->name('searches.store');
Route::get('/searches/{search}', [SearchController::class, 'show'])->name('searches.show');
