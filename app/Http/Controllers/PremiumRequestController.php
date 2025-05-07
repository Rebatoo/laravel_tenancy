<?php

namespace App\Http\Controllers;

use App\Models\PremiumRequest;
use Illuminate\Http\Request;

class PremiumRequestController extends Controller
{
    public function create()
    {
        // Check if tenant already has a pending request
        $pendingRequest = PremiumRequest::where('tenant_id', tenant('id'))
            ->where('status', 'pending')
            ->first();

        if ($pendingRequest) {
            return redirect()->route('tenant.home')
                ->with('error', 'You already have a pending premium request.');
        }

        return view('tenant.premium-request.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|min:10|max:1000'
        ]);

        PremiumRequest::create([
            'tenant_id' => tenant('id'),
            'message' => $validated['message'],
            'status' => 'pending'
        ]);

        return redirect()->route('tenant.home')
            ->with('success', 'Your premium request has been submitted successfully. We will review it shortly.');
    }

    public function status()
    {
        $request = PremiumRequest::where('tenant_id', tenant('id'))
            ->latest()
            ->first();

        return view('tenant.premium-request.status', compact('request'));
    }
} 