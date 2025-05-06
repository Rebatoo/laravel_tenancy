<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorizedAdmin extends Model
{
    protected $fillable = [
        'email',
        'added_by',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
} 