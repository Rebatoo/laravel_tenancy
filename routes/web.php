<?php
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantRegistrationController;
use App\Http\Controllers\AuthorizedAdminController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('homepage');
})->name('homepage');

Route::get('/homepage', function () {
    return view('homepage');
})->name('homepage');

Route::get('/test10', [TestController::class, 'index'])->name('test.index');

// Tenant Registration Routes
Route::get('/tenantregister', [TenantRegistrationController::class, 'showRegistrationForm'])->name('tenant.register.form');
Route::post('/tenantregister', [TenantRegistrationController::class, 'register'])->name('tenant.register');

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
    Route::patch('/tenants/{tenant}/verify', [TenantController::class, 'verifyTenant'])->name('tenants.verify');
});

// Authorized Admin Management Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/authorized-admins', [AuthorizedAdminController::class, 'index'])->name('authorized-admins.index');
    Route::get('/authorized-admins/create', [AuthorizedAdminController::class, 'create'])->name('authorized-admins.create');
    Route::post('/authorized-admins', [AuthorizedAdminController::class, 'store'])->name('authorized-admins.store');
    Route::delete('/authorized-admins/{authorizedAdmin}', [AuthorizedAdminController::class, 'destroy'])->name('authorized-admins.destroy');
    Route::patch('/authorized-admins/{authorizedAdmin}/toggle-status', [AuthorizedAdminController::class, 'toggleStatus'])->name('authorized-admins.toggle-status');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

require __DIR__.'/auth.php';
