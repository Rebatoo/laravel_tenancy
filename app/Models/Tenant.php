<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Support\Facades\Storage;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'is_premium',
        'verification_status',
        'temp_domain',
        'current_version',
        'pending_version',
        'customizations',
        'logo_path',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'password',
            'is_active',
            'is_premium',
            'verification_status',
            'temp_domain',
            'current_version',
            'pending_version',
            'logo_path',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * Store uploaded logo and save path
     */
    public function storeLogo($file)
    {
        // Delete old logo if exists
        if ($this->logo_path) {
            Storage::disk('public')->delete($this->logo_path);
        }

        // Store new logo
        $path = $file->store('tenant-logos', 'public');
        $this->update(['logo_path' => $path]);
        
        return $path;
    }

    /**
     * Get the logo URL
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo_path ? Storage::url($this->logo_path) : null;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function isPremium(): bool
    {
        return $this->is_premium;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    public function upgradeToPremium(): void
    {
        $this->update(['is_premium' => true]);
    }

    public function downgradeToBasic(): void
    {
        $this->update(['is_premium' => false]);
    }
}