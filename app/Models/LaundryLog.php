<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaundryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'worker_id',
        'laundry_type',
        'weight',
        'total_price',
        'status',
        'location'
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
} 