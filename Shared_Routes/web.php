<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Marketplace\ItemController;
use App\Http\Controllers\Marketplace\ChatController;
use App\Http\Controllers\Marketplace\ReviewController;
use App\Http\Controllers\Marketplace\TransactionController;
use App\Http\Controllers\Karma\KarmaController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Campus Connect Pro
|--------------------------------------------------------------------------
*/

// ── Public ─────────────────────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

// ── Auth (Guest Only) ─────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ── Authenticated Routes ──────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::get('/user/{id}', [ProfileController::class, 'show'])->name('user.profile');

    // ── Marketplace ────────────────────────────────────────────────
    Route::prefix('marketplace')->name('marketplace.')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('index');
        Route::get('/create', [ItemController::class, 'create'])->name('create');
        Route::post('/', [ItemController::class, 'store'])->name('store');
        Route::get('/my-listings', [ItemController::class, 'myListings'])->name('my-listings');
        Route::get('/{item}', [ItemController::class, 'show'])->name('show');
        Route::get('/{item}/edit', [ItemController::class, 'edit'])->name('edit');
        Route::put('/{item}', [ItemController::class, 'update'])->name('update');
        Route::delete('/{item}', [ItemController::class, 'destroy'])->name('destroy');
    });

    // ── Transactions ───────────────────────────────────────────────
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'history'])->name('index');
        Route::post('/initiate/{item}', [TransactionController::class, 'initiate'])->name('initiate');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
        Route::post('/{transaction}/complete', [TransactionController::class, 'complete'])->name('complete');
        Route::post('/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('cancel');
    });

    // ── Reviews ────────────────────────────────────────────────────
    Route::get('/review/{transaction}', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/review/{transaction}', [ReviewController::class, 'store'])->name('review.store');

    // ── Chat ───────────────────────────────────────────────────────
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::post('/start/{item}', [ChatController::class, 'startConversation'])->name('start');
        Route::get('/{conversation}', [ChatController::class, 'show'])->name('show');
        Route::post('/{conversation}/send', [ChatController::class, 'sendMessage'])->name('send');
        Route::get('/{conversation}/fetch', [ChatController::class, 'fetchMessages'])->name('fetch');
    });

    // ── Karma & Sustainability ─────────────────────────────────────
    Route::prefix('karma')->name('karma.')->group(function () {
        Route::get('/', [KarmaController::class, 'index'])->name('index');
        Route::get('/leaderboard', [KarmaController::class, 'leaderboard'])->name('leaderboard');
        Route::get('/sustainability', [KarmaController::class, 'sustainability'])->name('sustainability');
    });

    // ── Notifications ──────────────────────────────────────────────
    Route::get('/notifications', function () {
        $notifications = auth()->user()->notifications()->paginate(20);
        return view('notifications', compact('notifications'));
    })->name('notifications.index');

    Route::post('/notifications/mark-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    })->name('notifications.mark-read');
});

// ── Admin Routes ───────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/suspend', [AdminController::class, 'suspendUser'])->name('users.suspend');
    Route::post('/users/{user}/unsuspend', [AdminController::class, 'unsuspendUser'])->name('users.unsuspend');
    Route::get('/fraud-reports', [AdminController::class, 'fraudReports'])->name('fraud-reports');
    Route::post('/fraud-reports/{report}/resolve', [AdminController::class, 'resolveFraudReport'])->name('fraud-reports.resolve');
    Route::post('/fraud-scan', [AdminController::class, 'runFraudScan'])->name('fraud-scan');
    Route::get('/marketplace', [AdminController::class, 'marketplace'])->name('marketplace');
    Route::post('/marketplace/{item}/flag', [AdminController::class, 'flagItem'])->name('marketplace.flag');
});
