<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PremiumRequest extends Model
{
    protected $fillable = [
        'tenant_id',
        'message',
        'status',
        'admin_response'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
} 