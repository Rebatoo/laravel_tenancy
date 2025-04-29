<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TenantAdminController extends Controller
{
    public function index()
    {
        $admins = User::where('is_admin', true)->get();
        return view('tenant.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('tenant.admins.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'is_admin' => true,
        ]);

        return redirect()->route('tenant.admins.index')
            ->with('success', 'Admin user created successfully.');
    }
} 