<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Worker;

class WorkerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('tenant.workers.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('worker')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('tenant.workers.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('worker')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('tenant.workers.login');
    }

    public function dashboard()
    {
        $worker = Auth::guard('worker')->user();
        return view('tenant.workers.dashboard', compact('worker'));
    }
}
