<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantCustomization extends Model
{
    protected $fillable = [
        'logo_path',
        'primary_color',
        'secondary_color'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
} 