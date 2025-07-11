<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingMethod extends Model
{
    protected $fillable = [
        'name',
        'fee',
        'provider',
        'estimated_days',
        'status',
        'description',
    ];

    protected $casts = [
        'fee' => 'float',
        'estimated_days' => 'integer',
        'status' => 'boolean',
    ];

    public function shipping(): HasMany
    {
        return $this->hasMany(Shipping::class);
    }
}
