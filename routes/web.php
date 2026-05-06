<?php
use App\Http\Controllers\TutoringSessionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::get('/test', function () {
    return 'Working!';
});
use App\Http\Controllers\TutorProfileController;

Route::middleware(['auth'])->group(function () {
    Route::get('/tutors', [TutorProfileController::class, 'index']);
    Route::get('/tutors/create', [TutorProfileController::class, 'create']);
    Route::post('/tutors/store', [TutorProfileController::class, 'store']);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/tutoring-sessions', [TutoringSessionController::class, 'index']);
    Route::get('/tutoring-sessions/create/{tutorId}', [TutoringSessionController::class, 'create']);
    Route::post('/tutoring-sessions/store', [TutoringSessionController::class, 'store']);
});

use App\Http\Controllers\DoubtForumController;

Route::middleware(['auth'])->group(function () {
    Route::get('/doubt-forum', [DoubtForumController::class, 'index']);
    Route::post('/doubt-forum/question', [DoubtForumController::class, 'storeQuestion']);
    Route::post('/doubt-forum/answer/{id}', [DoubtForumController::class, 'storeAnswer']);
    Route::post('/doubt-forum/upvote/{id}', [DoubtForumController::class, 'upvote']);
    Route::post('/doubt-forum/downvote/{id}', [DoubtForumController::class, 'downvote']);
});