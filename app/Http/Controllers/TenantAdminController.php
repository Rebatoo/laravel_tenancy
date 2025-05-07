<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TenantAdminController extends Controller
{
    private const BASIC_PLAN_ADMIN_LIMIT = 5;
    private const PREMIUM_PLAN_ADMIN_LIMIT = 10;

    public function index()
    {
        $admins = User::where('is_admin', true)->get();
        $currentCount = $admins->count();
        $maxAdmins = tenant('is_premium') ? self::PREMIUM_PLAN_ADMIN_LIMIT : self::BASIC_PLAN_ADMIN_LIMIT;
        
        return view('tenant.admins.index', compact('admins', 'currentCount', 'maxAdmins'));
    }

    public function create()
    {
        $currentCount = User::where('is_admin', true)->count();
        $maxAdmins = tenant('is_premium') ? self::PREMIUM_PLAN_ADMIN_LIMIT : self::BASIC_PLAN_ADMIN_LIMIT;

        if ($currentCount >= $maxAdmins) {
            return redirect()->route('tenant.admins.index')
                ->with('error', 'You have reached the maximum number of admins allowed for your plan. ' . 
                    (tenant('is_premium') ? 'Upgrade to Premium for more admin slots.' : 'Upgrade to Premium for more admin slots.'));
        }

        return view('tenant.admins.create');
    }

    public function store(Request $request)
    {
        $currentCount = User::where('is_admin', true)->count();
        $maxAdmins = tenant('is_premium') ? self::PREMIUM_PLAN_ADMIN_LIMIT : self::BASIC_PLAN_ADMIN_LIMIT;

        if ($currentCount >= $maxAdmins) {
            return redirect()->route('tenant.admins.index')
                ->with('error', 'You have reached the maximum number of admins allowed for your plan. ' . 
                    (tenant('is_premium') ? 'Upgrade to Premium for more admin slots.' : 'Upgrade to Premium for more admin slots.'));
        }

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