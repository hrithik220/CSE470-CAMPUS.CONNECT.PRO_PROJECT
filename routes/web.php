<?php

use App\Http\Controllers\TutorProfileController;
use App\Http\Controllers\TutorReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/tutors', [TutorProfileController::class, 'index'])->name('tutors.index');

Route::middleware(['auth', 'role:tutor'])->group(function () {
    Route::get('/tutors/create', [TutorProfileController::class, 'create'])->name('tutors.create');
    Route::post('/tutors', [TutorProfileController::class, 'store'])->name('tutors.store');
    Route::get('/tutors/{tutorProfile}/edit', [TutorProfileController::class, 'edit'])->name('tutors.edit');
    Route::put('/tutors/{tutorProfile}', [TutorProfileController::class, 'update'])->name('tutors.update');
});

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::post('/tutors/{tutorProfile}/reviews', [TutorReviewController::class, 'store'])->name('tutors.reviews.store');
});

Route::get('/tutors/{tutorProfile}', [TutorProfileController::class, 'show'])->name('tutors.show');
