<?php

namespace App\Http\Controllers;

use App\Models\TenantCustomization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TenantCustomizationController extends Controller
{
    public function edit()
    {
        $customization = TenantCustomization::first();
        return view('tenant.customization.edit', compact('customization'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_color' => 'required|regex:/^#[a-fA-F0-9]{6}$/',
            'secondary_color' => 'required|regex:/^#[a-fA-F0-9]{6}$/',
        ]);

        $tenantId = tenant('id');
        $customization = TenantCustomization::where('tenant_id', $tenantId)->first();

        if (!$customization) {
            $customization = new TenantCustomization();
            $customization->tenant_id = $tenantId;
        }

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($customization->logo_path) {
                Storage::disk('public')->delete($customization->logo_path);
            }

            // Store new logo with tenant-specific path
            $path = $request->file('logo')->store("tenants/{$tenantId}/logos", 'public');
            $customization->logo_path = $path;
        }

        $customization->primary_color = $request->primary_color;
        $customization->secondary_color = $request->secondary_color;
        $customization->save();

        return redirect()->route('tenant.customization.edit')
            ->with('success', 'Customization settings updated successfully');
    }
} 