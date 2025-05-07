Route::middleware(['auth', 'admin'])->group(function () {
    // ... existing code ...
    
    // Premium Requests
    Route::get('/premium-requests', [App\Http\Controllers\Admin\PremiumRequestController::class, 'index'])
        ->name('admin.premium-requests.index');
    Route::get('/premium-requests/{premiumRequest}', [App\Http\Controllers\Admin\PremiumRequestController::class, 'show'])
        ->name('admin.premium-requests.show');
    Route::put('/premium-requests/{premiumRequest}', [App\Http\Controllers\Admin\PremiumRequestController::class, 'update'])
        ->name('admin.premium-requests.update');
}); 