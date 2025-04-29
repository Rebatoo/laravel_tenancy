<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Artisan;

class TenantRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('tenant.register');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:tenants,email',
            'domain_name' => 'required|string|max:255|unique:domains,domain',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create the tenant with pending status
        $tenant = Tenant::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'is_active' => false, // Set to false until approved by admin
            'is_premium' => false, // Default to basic plan
        ]);
        
        // Create the domain
        $tenant->domains()->create([
            'domain' => $validatedData['domain_name'].'.'.config('app.domain')
        ]);

        // Initialize tenancy to create the tenant's database
        tenancy()->initialize($tenant);

        // Run migrations in the tenant's database
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations',
            '--force' => true,
        ]);

        // Create the admin user in the tenant's database
        \App\Models\User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'is_admin' => true,
        ]);

        // End tenancy
        tenancy()->end();

        return redirect()->route('homepage')
            ->with('success', 'Your tenant registration has been submitted and is pending approval.');
    }
} 