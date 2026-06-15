<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Service extends Model
{
    protected $fillable = [
        'code',
        'name',
        'category',
        'type',
        'description',
        'base_price',
        'is_active',
        'delivery_sla_hours',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'is_active' => 'boolean',
            'metadata' => 'array',
        ];
    }

    /**
     * @return BelongsToMany<Order>
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withPivot([
            'quantity',
            'unit_price',
            'line_total',
            'metadata',
        ])->withTimestamps();
    }
}
