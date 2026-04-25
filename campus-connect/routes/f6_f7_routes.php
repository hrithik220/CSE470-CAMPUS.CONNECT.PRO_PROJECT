<?php

use App\Http\Controllers\KarmaController;
use App\Http\Controllers\CarbonFootprintController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| F6 — Karma Points Award System
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('karma')->name('karma.')->group(function () {
    Route::get('/',            [KarmaController::class, 'index'])       ->name('index');
    Route::get('/leaderboard', [KarmaController::class, 'leaderboard']) ->name('leaderboard');
});

/*
|--------------------------------------------------------------------------
| F7 — Carbon Footprint Calculator for Rides
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('carbon')->name('carbon.')->group(function () {
    Route::get('/',         [CarbonFootprintController::class, 'index'])   ->name('index');
    Route::post('/preview', [CarbonFootprintController::class, 'preview']) ->name('preview');
    Route::post('/',        [CarbonFootprintController::class, 'store'])   ->name('store');
});
