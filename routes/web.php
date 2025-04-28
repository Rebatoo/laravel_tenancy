<?php
use App\Http\Controllers\TenantController;
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

    Route::resource('tenants', TenantController::class);
    Route::patch('/tenants/{tenant}/update-plan', [TenantController::class, 'updatePlan'])->name('tenants.updatePlan');
    Route::patch('/tenants/{tenant}/update-status', [TenantController::class, 'updateStatus'])->name('tenants.updateStatus');
});

require __DIR__.'/auth.php';
