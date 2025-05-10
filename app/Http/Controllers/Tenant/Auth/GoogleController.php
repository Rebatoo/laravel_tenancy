<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Stancl\Tenancy\Database\Models\Domain;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        \Log::info('Google callback reached'); // Debug log
        try {
            $googleUser = Socialite::driver('google')->user();
            \Log::info('Google user data:', (array) $googleUser); // Debug log
            
            // Check if tenant exists
            $tenant = Tenant::where('email', $googleUser->email)->first();

            if (!$tenant) {
                // Store Google data in the session for registration
                session()->put('google_tenant_data', [
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                ]);
                return redirect()->route('tenant.register.form')
                    ->with('success', 'Google authentication successful. Please complete your registration.');
            }

            // Log in the tenant
            Auth::guard('tenant')->login($tenant);
            return redirect()->route('tenant.home');

        } catch (\Exception $e) {
            \Log::error('Google callback error:', ['error' => $e->getMessage()]); // Debug log
            return redirect()->route('tenant.login')
                ->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }
}
