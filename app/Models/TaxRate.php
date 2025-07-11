<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'rate',
        'description',
        'is_active',
    ];

    protected $casts = [
        'rate' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Lấy thuế theo phần trăm
     */
    public function getRatePercentageAttribute(): string
    {
        return $this->rate . '%';
    }
}
