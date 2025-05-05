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

        // Store the domain name in the tenant data for later use
        $validatedData['temp_domain'] = $validatedData['domain_name'];
        unset($validatedData['domain_name']); // Remove it from direct tenant creation

        // Create the tenant with pending status only
        $tenant = Tenant::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'is_active' => false,
            'is_premium' => false,
            'verification_status' => 'pending',
            'temp_domain' => $validatedData['temp_domain'], // Store temporarily
        ]);

        return redirect()->route('homepage')
            ->with('success', 'Your tenant registration has been submitted and is pending admin verification. You will be notified once approved.');
    }
} 