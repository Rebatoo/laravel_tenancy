<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

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
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_premium' => 'boolean',
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
            'created_at',
            'updated_at',
        ];
    }
    public function setPasswordAttribute($value)
    {
       return $this->attributes['password'] =  bcrypt($value);
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

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($tenant) {
            \Log::info('Tenant updating:', [
                'id' => $tenant->id,
                'changes' => $tenant->getDirty(),
            ]);
        });
    }
}