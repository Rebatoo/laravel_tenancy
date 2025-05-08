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

        // Check if Google data exists in session
        $googleData = session('google_tenant_data');

        // Create the tenant
        $tenant = Tenant::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'google_id' => $googleData['google_id'] ?? null, // Save Google ID if available
            'is_active' => false,
            'is_premium' => false,
            'verification_status' => 'pending',
            'temp_domain' => $validatedData['domain_name'],
        ]);

        // Clear the session data
        session()->forget('google_tenant_data');

        return redirect()->route('homepage')
            ->with('success', 'Your tenant registration has been submitted and is pending admin verification.');
    }
} 