<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TenantHomeController extends Controller
{
    public function index()
    {
        // Get the admin user (the first user created in the tenant's database)
        $admin = User::where('is_admin', true)->first();
        
        // Get all users
        $users = User::all();

        return view('tenant.home', [
            'admin' => $admin,
            'users' => $users
        ]);
    }
}
