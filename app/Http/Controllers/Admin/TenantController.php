<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\TenantApprovalEmail;

class TenantController extends Controller
{
    public function approve(Tenant $tenant)
    {
        // Generate a new password (or use the temp password)
        $password = Str::random(8);
        $tenant->update([
            'password' => Hash::make($password),
            'is_active' => true,
        ]);

        // Send email to tenant with credentials
        Mail::to($tenant->email)->send(new TenantApprovalEmail($tenant, $password));

        return redirect()->back()
            ->with('success', "Tenant approved. Login details sent to {$tenant->email}.");
    }
} 