<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class TenantRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        $googleData = session('google_tenant_data');
        return view('tenant.register', [
            'googleData' => $googleData,
        ]);
    }

    public function register(Request $request)
    {
        $googleData = session('google_tenant_data');

        if ($googleData) {
            $validatedData = $request->validate([
                'domain_name' => 'required|string|max:255|unique:domains,domain',
            ]);

            // Merge Google data with validated data
            $validatedData = array_merge($validatedData, [
                'name' => $googleData['name'],
                'email' => $googleData['email'],
                'password' => Str::random(12), // Auto-generate a password
            ]);
        } else {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:tenants,email',
                'domain_name' => 'required|string|max:255|unique:domains,domain',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
        }

        // Store the domain name in the tenant data for later use
        $validatedData['temp_domain'] = $validatedData['domain_name'];
        unset($validatedData['domain_name']);

        // Create the tenant
        $tenant = Tenant::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'is_active' => false,
            'is_premium' => false,
            'verification_status' => 'pending',
            'temp_domain' => $validatedData['temp_domain'],
        ]);

        // Clear the Google data from the session
        session()->forget('google_tenant_data');

        return redirect()->route('homepage')
            ->with('success', 'Your tenant registration has been submitted and is pending admin verification. You will be notified once approved.');
    }
} 