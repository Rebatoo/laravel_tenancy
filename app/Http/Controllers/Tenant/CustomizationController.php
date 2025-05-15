<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\TenantCustomizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomizationController extends Controller
{
    public function __construct(
        protected TenantCustomizationService $customizationService
    ) {}

    public function getCustomizations()
    {
        $tenant = tenant();
        return response()->json($tenant->customizations ?? []);
    }

    public function updateCustomization(Request $request)
    {
        $request->validate([
            'key' => 'sometimes|required|string',
            'value' => 'sometimes|required',
            'logo' => 'sometimes|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('public/tenant-logos');
            $url = Storage::url($path);
            $this->customizationService->setCustomization(tenant(), 'logo_url', $url);
        }

        if ($request->filled('key') && $request->filled('value')) {
            $this->customizationService->setCustomization(
                tenant(),
                $request->input('key'),
                $request->input('value')
            );
        }

        return response()->json(['message' => 'Customizations updated successfully']);
    }

    public function removeCustomization(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        $this->customizationService->removeCustomization(
            tenant(),
            $request->input('key')
        );

        return response()->json(['message' => 'Customization removed successfully']);
    }

    public function store(Request $request)
    {
        $tenant = tenant();
        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found.'], 404);
        }

        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $customizations = $tenant->customizations ?? [];
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if (isset($customizations['logo'])) {
                Storage::disk('tenant_assets')->delete($customizations['logo']);
            }

            // Store using tenant_assets disk
            $path = $request->file('logo')->store('tenant-logos', 'tenant_assets');
            $customizations['logo'] = $path;
            $tenant->update(['customizations' => $customizations]);
        }

        return redirect()->back()->with('success', 'Logo uploaded!');
    }
} 