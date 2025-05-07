<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PremiumRequest;
use App\Models\Tenant;
use Illuminate\Http\Request;

class PremiumRequestController extends Controller
{
    public function index()
    {
        $requests = PremiumRequest::with('tenant')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.premium-requests.index', compact('requests'));
    }

    public function show(PremiumRequest $premiumRequest)
    {
        return view('admin.premium-requests.show', compact('premiumRequest'));
    }

    public function update(Request $request, PremiumRequest $premiumRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_response' => 'required|string|min:10|max:1000'
        ]);

        $premiumRequest->update([
            'status' => $validated['status'],
            'admin_response' => $validated['admin_response']
        ]);

        // If approved, upgrade the tenant to premium
        if ($validated['status'] === 'approved') {
            $tenant = Tenant::find($premiumRequest->tenant_id);
            if ($tenant) {
                $tenant->upgradeToPremium();
            }
        }

        return redirect()->route('admin.premium-requests.index')
            ->with('success', 'Premium request has been ' . $validated['status']);
    }
} 