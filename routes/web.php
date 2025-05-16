<?php
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantRegistrationController;
use App\Http\Controllers\AuthorizedAdminController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Tenant\Auth\GoogleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('homepage');
})->name('homepage');

Route::get('/homepage', function () {
    return view('homepage');
})->name('homepage');

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

Route::get('/tenant/auth/google/callback', [GoogleController::class, 'callback'])->name('tenant.google.callback');

Route::get('/tenant-assets/{tenant_id}/{path}', function ($tenant_id, $path) {
    $storagePath = storage_path("tenant{$tenant_id}/app/public/{$path}");
    \Log::info("Attempting to access file: {$storagePath}");

    if (!file_exists($storagePath)) {
        \Log::error("File not found: {$storagePath}");
        abort(404, "File not found: {$storagePath}");
    }
    return response()->file($storagePath);
})->where('path', '.*');

Route::get('/debug-logo', function() {
    $tenant = tenant();
    return response()->json([
        'logo_path' => $tenant->logo_path,
        'url' => $tenant->logo_path ? Storage::url($tenant->logo_path) : null,
        'exists' => $tenant->logo_path ? Storage::exists($tenant->logo_path) : false,
        'files' => Storage::files('tenant-logos')
    ]);
});

Route::get('/test-logo-access', function() {
    $path = 'tenant-logos/q4ms704mKELHnQ8N750wYiZ7LzcsEzIDAzYjrb2e.png';
    $absolutePath = storage_path('app/public/'.$path);
    
    return response()->json([
        'file_exists' => file_exists($absolutePath),
        'is_readable' => is_readable($absolutePath),
        'file_size' => file_exists($absolutePath) ? filesize($absolutePath).' bytes' : 'N/A',
        'url' => asset(Storage::url($path)),
        'symlink_valid' => is_link(public_path('storage')),
        'symlink_target' => readlink(public_path('storage'))
    ]);
});

Route::get('/verify-logo', function() {
    $path = 'tenant-logos/'.basename(tenant()->logo_path);
    $absolutePath = storage_path('app/public/'.$path);
    
    try {
        return response()->json([
            'status' => 'success',
            'exists' => file_exists($absolutePath),
            'readable' => is_readable($absolutePath),
            'size' => file_exists($absolutePath) ? filesize($absolutePath).' bytes' : 0,
            'url' => asset(Storage::url($path)),
            'mime_type' => file_exists($absolutePath) ? mime_content_type($absolutePath) : null,
            'headers' => get_headers(asset(Storage::url($path)), 1)
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
});

require __DIR__.'/auth.php';
