<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant;

class TenantAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('tenant.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        \Log::info('Login attempt', ['email' => $credentials['email']]);

        // Check tenant status
        $tenant = Tenant::where('email', $credentials['email'])->first();

        if (!$tenant) {
            \Log::warning('Tenant not found', ['email' => $credentials['email']]);
            return back()->withErrors([
                'email' => 'Tenant not found.',
            ]);
        }

        if (!$tenant->is_active || $tenant->verification_status !== 'verified') {
            \Log::warning('Tenant not active/verified', [
                'email' => $credentials['email'],
                'is_active' => $tenant->is_active,
                'verification_status' => $tenant->verification_status
            ]);
            return back()->withErrors([
                'email' => 'Tenant not approved yet.',
            ]);
        }

        // Attempt login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('tenant.home'));
        }

        \Log::warning('Invalid credentials', ['email' => $credentials['email']]);
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('worker')->check()) {
            Auth::guard('worker')->logout();
        } else {
            Auth::logout();
        }
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('tenant-login');
    }

    public function workerDashboard()
    {
        $worker = Auth::guard('worker')->user();
        return view('tenant.workers.dashboard', compact('worker'));
    }
} 