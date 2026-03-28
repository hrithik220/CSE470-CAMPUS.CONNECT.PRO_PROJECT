<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hrithik\MarketplaceItemController;
use App\Http\Controllers\Hrithik\ChatMessageController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/marketplace', [MarketplaceItemController::class, 'index'])->name('marketplace.index');
Route::get('/marketplace/create', [MarketplaceItemController::class, 'create'])->name('marketplace.create');
Route::post('/marketplace', [MarketplaceItemController::class, 'store'])->name('marketplace.store');
Route::get('/marketplace/my/listings', [MarketplaceItemController::class, 'myListings'])->name('marketplace.my-listings');
Route::get('/marketplace/{item}', [MarketplaceItemController::class, 'show'])->name('marketplace.show');
Route::get('/marketplace/{item}/edit', [MarketplaceItemController::class, 'edit'])->name('marketplace.edit');
Route::put('/marketplace/{item}', [MarketplaceItemController::class, 'update'])->name('marketplace.update');
Route::delete('/marketplace/{item}', [MarketplaceItemController::class, 'destroy'])->name('marketplace.destroy');
Route::get('/marketplace/{item}/chat', [ChatMessageController::class, 'show'])->name('marketplace.chat');
Route::post('/marketplace/{item}/chat', [ChatMessageController::class, 'store'])->name('marketplace.chat.store');
Route::get('/inbox', [ChatMessageController::class, 'inbox'])->name('inbox');