<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Stancl\Tenancy\Database\Models\Domain;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Schema;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if tenant exists
            $tenant = Tenant::where('email', $googleUser->email)->first();

            if (!$tenant) {
                // Option 1: Redirect to registration with Google data
                session()->put('google_tenant_data', [
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                ]);
                return redirect()->route('tenant.register.form')
                    ->with('google_data', true);
            }

            // Log in the tenant
            Auth::guard('tenant')->login($tenant);
            return redirect()->route('tenant.home');

        } catch (\Exception $e) {
            return redirect()->route('tenant.login')
                ->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }
}
