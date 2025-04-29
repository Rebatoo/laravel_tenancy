<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantHomeController extends Controller
{
    public function index()
    {
        // Get the currently authenticated admin user
        $admin = Auth::user();
        
        // Get all users
        $users = User::all();

        return view('tenant.home', [
            'admin' => $admin,
            'users' => $users
        ]);
    }
}
