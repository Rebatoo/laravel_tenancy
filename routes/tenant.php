<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\TenantHomeController;
use App\Http\Controllers\TenantAuthController;
use App\Http\Controllers\TenantWorkerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LaundryLogController;
use App\Http\Controllers\TenantAdminController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\PremiumRequestController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // Tenant Authentication Routes (completely separate from central app)
    Route::get('/tenant-login', [TenantAuthController::class, 'showLoginForm'])->name('tenant-login');
    Route::post('/tenant-login', [TenantAuthController::class, 'login']);
    Route::post('/tenant-logout', [TenantAuthController::class, 'logout'])->name('tenant-logout');

    // Guest homepage for tenant domain
    Route::get('/', function () {
        if (auth()->check()) {
            return redirect()->route('tenant.home');
        }
        return view('tenant.auth.login');
    });

    // Protected Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/home', [TenantHomeController::class, 'index'])->name('tenant.home');
        
        // Admin Management Routes
        Route::get('/admins', [TenantAdminController::class, 'index'])->name('tenant.admins.index');
        Route::get('/admins/create', [TenantAdminController::class, 'create'])->name('tenant.admins.create');
        Route::post('/admins', [TenantAdminController::class, 'store'])->name('tenant.admins.store');
        
        // Worker Management Routes
        Route::get('/workers', [TenantWorkerController::class, 'index'])->name('tenant.workers.index');
        Route::get('/workers/create', [TenantWorkerController::class, 'create'])->name('tenant.workers.create');
        Route::post('/workers', [TenantWorkerController::class, 'store'])->name('tenant.workers.store');

        // Analytics Route (Premium Feature)
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('tenant.analytics');

        // Premium Request Routes
        Route::get('/premium-request', [PremiumRequestController::class, 'create'])->name('tenant.premium-request.create');
        Route::post('/premium-request', [PremiumRequestController::class, 'store'])->name('tenant.premium-request.store');
        Route::get('/premium-request/status', [PremiumRequestController::class, 'status'])->name('tenant.premium-request.status');
    });

    // Customer Management Routes (accessible by both auth and worker)
    Route::middleware(['auth:worker,web'])->group(function () {
        Route::resource('customers', CustomerController::class)->names([
            'index' => 'tenant.customers.index',
            'create' => 'tenant.customers.create',
            'store' => 'tenant.customers.store',
            'show' => 'tenant.customers.show',
            'edit' => 'tenant.customers.edit',
            'update' => 'tenant.customers.update',
            'destroy' => 'tenant.customers.destroy',
        ]);

        // Laundry Log Routes
        Route::resource('laundry-logs', LaundryLogController::class)->names([
            'index' => 'tenant.laundry_logs.index',
            'create' => 'tenant.laundry_logs.create',
            'store' => 'tenant.laundry_logs.store',
            'show' => 'tenant.laundry_logs.show',
            'edit' => 'tenant.laundry_logs.edit',
            'update' => 'tenant.laundry_logs.update',
            'destroy' => 'tenant.laundry_logs.destroy',
        ]);
    });

    // Worker Dashboard Route (protected by worker guard)
    Route::middleware(['auth:worker'])->group(function () {
        Route::get('/worker/dashboard', [TenantAuthController::class, 'workerDashboard'])->name('tenant.workers.dashboard');
    });
});
