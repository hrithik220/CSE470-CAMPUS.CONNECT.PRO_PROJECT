














use App\Http\Controllers\RideController;

// Grouping routes that require users to be logged in
Route::middleware(['auth'])->group(function () {
    Route::get('/rides', [RideController::class, 'index'])->name('rides.index'); 
    Route::get('/rides/create', [RideController::class, 'create'])->name('rides.create'); 
    Route::post('/rides', [RideController::class, 'store'])->name('rides.store'); 
    Route::post('/rides/{ride}/request', [RideController::class, 'requestJoin'])->name('rides.request'); 
    Route::get('/rides/history', [RideController::class, 'history'])->name('rides.history'); 
});