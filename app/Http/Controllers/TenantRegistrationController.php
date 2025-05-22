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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email',
            'domain_name' => 'required|string|unique:domains,domain',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create tenant with domain
        $tenant = Tenant::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'is_active' => false, // Admin must approve
            'verification_status' => 'pending',
            'temp_domain' => $validated['domain_name'], // Store requested domain temporarily
        ]);

        // Create domain immediately (don't use temp_domain)
        $tenant->domains()->create([
            'domain' => $validated['domain_name'] . '.' . config('tenancy.central_domains')[0],
        ]);

        return redirect()->route('homepage')
            ->with('success', 'Registration submitted for admin approval');
    }
} 