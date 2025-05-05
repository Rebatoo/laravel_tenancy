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
        $validated = $request->validate([
            'verification_status' => 'required|in:verified,rejected',
        ]);

        if ($validated['verification_status'] === 'verified') {
            try {
                // Create the domain
                $tenant->domains()->create([
                    'domain' => $tenant->temp_domain . '.' . config('app.domain')
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
                    'name' => $tenant->name,
                    'email' => $tenant->email,
                    'password' => $tenant->password, // Already hashed in the Tenant model
                    'is_admin' => true,
                ]);

                // End tenancy
                tenancy()->end();

                // Update tenant status
                $tenant->update([
                    'verification_status' => 'verified',
                    'is_active' => true,
                    'temp_domain' => null // Clear temporary domain
                ]);

                // Send verification email
                try {
                    $emailData = [
                        'tenant_name' => $tenant->name,
                        'status' => 'verified',
                        'login_url' => $tenant->domains->first()->domain
                    ];
                    
                    Mail::to($tenant->email)->send(new \App\Mail\TenantVerificationStatus($emailData));
                } catch (\Exception $e) {
                    // Log email sending failure but don't stop the process
                    \Log::error('Failed to send verification email: ' . $e->getMessage());
                }

                return redirect()->route('tenants.index')
                    ->with('success', 'Tenant has been verified and initialized successfully.');

            } catch (\Exception $e) {
                // If anything fails during initialization, mark as rejected
                $tenant->update([
                    'verification_status' => 'rejected',
                    'is_active' => false
                ]);

                return redirect()->route('tenants.index')
                    ->with('error', 'Failed to initialize tenant: ' . $e->getMessage());
            }
        } else {
            // Handle rejection
            $tenant->update([
                'verification_status' => 'rejected',
                'is_active' => false
            ]);

            // Send rejection email
            try {
                $emailData = [
                    'tenant_name' => $tenant->name,
                    'status' => 'rejected',
                    'login_url' => null
                ];
                
                Mail::to($tenant->email)->send(new \App\Mail\TenantVerificationStatus($emailData));
            } catch (\Exception $e) {
                // Log email sending failure but don't stop the process
                \Log::error('Failed to send rejection email: ' . $e->getMessage());
            }

            return redirect()->route('tenants.index')
                ->with('success', 'Tenant has been rejected.');
        }
    }
}
