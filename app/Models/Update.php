<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Update extends Model
{
    use HasFactory;

    protected $fillable = [
        'version',
        'title',
        'description',
        'changes',
        'is_required',
        'is_published',
        'published_at',
        'type'
    ];

    protected $casts = [
        'changes' => 'array',
        'is_required' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime'
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->whereNotNull('published_at');
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }
}
