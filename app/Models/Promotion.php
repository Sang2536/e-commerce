<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'value',
        'usage_limit',
        'used',
        'is_active',
        'is_public',
        'starts_at',
        'ends_at',
    ];

    public function isAvailable(): bool
    {
        return $this->is_active
            && ($this->starts_at === null || $this->starts_at <= now())
            && ($this->ends_at === null || $this->ends_at >= now())
            && ($this->usage_limit === null || $this->used < $this->usage_limit);
    }
}
