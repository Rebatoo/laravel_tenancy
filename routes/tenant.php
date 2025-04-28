<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\TenantHomeController;
use App\Http\Controllers\TenantAuthController;
use App\Http\Controllers\TenantWorkerController;

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
    // Authentication Routes
    Route::get('/tenant-login', [TenantAuthController::class, 'showLoginForm'])->name('tenant-login');
    Route::post('/tenant-login', [TenantAuthController::class, 'login']);
    Route::post('/tenant-logout', [TenantAuthController::class, 'logout'])->name('tenant-logout');

    // Protected Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/', [TenantHomeController::class, 'index'])->name('tenant.home');
        
        // Worker Management Routes
        Route::get('/workers', [TenantWorkerController::class, 'index'])->name('tenant.workers.index');
        Route::get('/workers/create', [TenantWorkerController::class, 'create'])->name('tenant.workers.create');
        Route::post('/workers', [TenantWorkerController::class, 'store'])->name('tenant.workers.store');
    });
});
