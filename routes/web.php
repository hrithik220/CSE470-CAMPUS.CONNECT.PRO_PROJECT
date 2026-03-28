<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// ── Hrithik: Marketplace ──
use App\Http\Controllers\Hrithik\MarketplaceItemController;
use App\Http\Controllers\Hrithik\ChatMessageController;
use App\Http\Controllers\Hrithik\TransactionController;
use App\Http\Controllers\Hrithik\ReviewController;

// ── Ramisha: Karma & Sustainability ──
// use App\Http\Controllers\Ramisha\KarmaLogController;
// use App\Http\Controllers\Ramisha\BadgeController;
// use App\Http\Controllers\Ramisha\SustainabilityController;
// use App\Http\Controllers\Ramisha\FraudFlagController;

// ── Nahid: Tutoring + Academic ──
// use App\Http\Controllers\Nahid\TutorProfileController;
// use App\Http\Controllers\Nahid\TutoringSessionController;
// use App\Http\Controllers\Nahid\DoubtController;
// use App\Http\Controllers\Nahid\AnswerController;
// use App\Http\Controllers\Nahid\DeadlineController;

// ── Pronoy: Ride Sharing ──
// use App\Http\Controllers\Pronoy\RideOfferController;
// use App\Http\Controllers\Pronoy\RideRequestController;
// use App\Http\Controllers\Pronoy\RideHistoryController;

// ─────────────────────────────────────────────────────────────
// Public routes
// ─────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

// Auth
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// ─────────────────────────────────────────────────────────────
// Authenticated routes
// ─────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // ── HRITHIK: Marketplace (Feature 1 — Item Listing) ──────
    Route::prefix('marketplace')->name('marketplace.')->group(function () {
        Route::get('/',              [MarketplaceItemController::class, 'index'])->name('index');
        Route::get('/create',        [MarketplaceItemController::class, 'create'])->name('create');
        Route::post('/',             [MarketplaceItemController::class, 'store'])->name('store');
        Route::get('/{item}',        [MarketplaceItemController::class, 'show'])->name('show');
        Route::get('/{item}/edit',   [MarketplaceItemController::class, 'edit'])->name('edit');
        Route::put('/{item}',        [MarketplaceItemController::class, 'update'])->name('update');
        Route::delete('/{item}',     [MarketplaceItemController::class, 'destroy'])->name('destroy');
        Route::get('/my/listings',   [MarketplaceItemController::class, 'myListings'])->name('my-listings');

        // ── HRITHIK: Chat (Feature 2 — In-app Chat) ─────────
        Route::get('/{item}/chat',   [ChatMessageController::class, 'show'])->name('chat');
        Route::post('/{item}/chat',  [ChatMessageController::class, 'store'])->name('chat.store');
    });

    // Chat inbox
    Route::get('/inbox', [ChatMessageController::class, 'inbox'])->name('inbox');

    // ── RAMISHA routes (uncomment when ready) ────────────────
    // Route::prefix('karma')->name('karma.')->group(function () { ... });

    // ── NAHID routes (uncomment when ready) ──────────────────
    // Route::prefix('tutoring')->name('tutoring.')->group(function () { ... });

    // ── PRONOY routes (uncomment when ready) ─────────────────
    // Route::prefix('rides')->name('rides.')->group(function () { ... });
});
