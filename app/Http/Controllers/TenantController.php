<?php

namespace App\Http\Controllers;
use Stancl\Tenancy\Facades\Tenancy;
use Illuminate\Validation\Rules;
use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::with('domains')->get();
        //dd($tenants->toArray());
        return view('tenants.index', ['tenants' => $tenants]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validation
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'domain_name' => 'required|string|max:255|unique:domains,domain',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create the tenant with verified status since it's created by admin
        $tenant = Tenant::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'is_active' => true, // Active by default when created by admin
            'is_premium' => false,
            'verification_status' => 'verified', // Automatically verified when created by admin
        ]);
        
        // Create the domain
        $tenant->domains()->create([
            'domain' => $validatedData['domain_name'].'.'.config('app.domain')
        ]);

        // Initialize tenancy to create the tenant's database
        tenancy()->initialize($tenant);

        // Run migrations in the tenant's database
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations',
            '--force' => true,
        ]);

        // Create the admin user in the tenant's database
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'is_admin' => true,
        ]);

        // End tenancy
        tenancy()->end();

        return redirect()->route('tenants.index')->with('success', 'Tenant created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        return view('tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        return view('tenants.edit', compact('tenant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        //
    }

    /**
     * Update the plan (is_premium) for the specified tenant.
     */
    public function updatePlan(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'is_premium' => 'required|boolean',
        ]);
        $tenant->is_premium = $validated['is_premium'];
        $tenant->save();
        return redirect()->route('tenants.index')->with('success', 'Tenant plan updated successfully.');
    }

    /**
     * Update the status (is_active) for the specified tenant.
     */
    public function updateStatus(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean',
        ]);
        $tenant->is_active = $validated['is_active'];
        $tenant->save();
        return redirect()->route('tenants.index')->with('success', 'Tenant status updated successfully.');
    }

    public function verifyTenant(Request $request, Tenant $tenant)
    {
        \Log::info('Verification request data:', $request->all()); // Debug: Log the request

        $validated = $request->validate([
            'verification_status' => 'required|in:verified,rejected',
        ]);

        \Log::info('Validated status:', ['status' => $validated['verification_status']]);

        try {
            if ($validated['verification_status'] === 'verified') {
                // Skip if already initialized
                if (!$tenant->domains()->exists()) {
                    $tenant->domains()->create([
                        'domain' => $tenant->temp_domain . '.' . config('app.domain'),
                    ]);

                    // Manually initialize tenancy (bypass TenantCreated event)
                    tenancy()->initialize($tenant);
                    Artisan::call('migrate', [
                        '--database' => 'tenant',
                        '--path' => 'database/migrations',
                        '--force' => true,
                    ]);
                    tenancy()->end();
                }

                // Update tenant status
                $tenant->update([
                    'verification_status' => 'verified',
                    'is_active' => true,
                    'temp_domain' => null,
                ]);

                \Log::info('Tenant approved:', ['tenant_id' => $tenant->id]);

                return redirect()->route('tenants.index')
                    ->with('success', 'Tenant approved and activated successfully.');
            } else {
                // Reject the tenant
                $tenant->update([
                    'verification_status' => 'rejected',
                    'is_active' => false,
                ]);

                return redirect()->route('tenants.index')
                    ->with('success', 'Tenant rejected.');
            }
        } catch (\Exception $e) {
            \Log::error('Tenant verification failed:', [
                'error' => $e->getMessage(),
                'tenant_id' => $tenant->id,
            ]);

            // Only mark as rejected if the error is critical
            if (str_contains($e->getMessage(), 'database') || str_contains($e->getMessage(), 'migrate')) {
                $tenant->update([
                    'verification_status' => 'rejected',
                    'is_active' => false,
                ]);
            }

            return redirect()->route('tenants.index')
                ->with('error', 'Failed to verify tenant: ' . $e->getMessage());
        }
    }
}
