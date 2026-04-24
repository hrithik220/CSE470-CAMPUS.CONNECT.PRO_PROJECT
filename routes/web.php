<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hrithik\MarketplaceItemController;
use App\Http\Controllers\Hrithik\ChatMessageController;
use App\Http\Controllers\Hrithik\TransactionController;
use App\Http\Controllers\Hrithik\ReviewController;

Route::get('/', function () {
    return view('welcome');
});

// ── HRITHIK: Marketplace (F1 — Item Listing & Search) ────────
Route::get('/marketplace', [MarketplaceItemController::class, 'index'])->name('marketplace.index');
Route::get('/marketplace/create', [MarketplaceItemController::class, 'create'])->name('marketplace.create');
Route::post('/marketplace', [MarketplaceItemController::class, 'store'])->name('marketplace.store');
Route::get('/marketplace/my/listings', [MarketplaceItemController::class, 'myListings'])->name('marketplace.my-listings');
Route::get('/marketplace/{item}/edit', [MarketplaceItemController::class, 'edit'])->name('marketplace.edit');
Route::put('/marketplace/{item}', [MarketplaceItemController::class, 'update'])->name('marketplace.update');
Route::delete('/marketplace/{item}', [MarketplaceItemController::class, 'destroy'])->name('marketplace.destroy');
Route::get('/marketplace/{item}', [MarketplaceItemController::class, 'show'])->name('marketplace.show');

// ── HRITHIK: Chat (F2 — In-app Chat) ─────────────────────────
Route::get('/marketplace/{item}/chat', [ChatMessageController::class, 'show'])->name('marketplace.chat');
Route::post('/marketplace/{item}/chat', [ChatMessageController::class, 'store'])->name('marketplace.chat.store');
Route::get('/inbox', [ChatMessageController::class, 'inbox'])->name('inbox');

// ── HRITHIK: Transaction & Credibility (F3) ───────────────────
Route::get('/seller/{sellerId}/profile', [TransactionController::class, 'sellerProfile'])->name('marketplace.seller.profile');
Route::post('/marketplace/{item}/buy', [TransactionController::class, 'store'])->name('marketplace.buy');
Route::post('/marketplace/{item}/complete', [TransactionController::class, 'complete'])->name('marketplace.complete');

// ── HRITHIK: Review & Rating (F4) ─────────────────────────────
Route::get('/marketplace/{item}/review', [ReviewController::class, 'create'])->name('marketplace.review.create');
Route::post('/marketplace/{item}/review', [ReviewController::class, 'store'])->name('marketplace.review.store');
Route::get('/seller/{sellerId}/reviews', [ReviewController::class, 'sellerReviews'])->name('marketplace.seller.reviews');
