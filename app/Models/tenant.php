<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;
    
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'password',
            'is_premium',
            'is_active'
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
}