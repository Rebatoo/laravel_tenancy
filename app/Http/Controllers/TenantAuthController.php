<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Support\Facades\Hash;

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

        // Check if email exists in users table
        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user, $request->filled('remember'));
            return redirect()->intended(route('tenant.home'));
        }

        // If not found in users, check workers table
        $worker = Worker::where('email', $credentials['email'])->first();

        if ($worker && Hash::check($credentials['password'], $worker->password)) {
            Auth::guard('worker')->login($worker, $request->filled('remember'));
            return redirect()->intended(route('tenant.workers.dashboard'));
        }

        // If neither found, return with error
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
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