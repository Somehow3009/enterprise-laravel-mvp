<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Customer extends Model
{
    protected $fillable = [
        'code',
        'name',
        'email',
        'phone',
        'company_name',
        'status',
        'metadata',
        'last_activity_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'last_activity_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<Order>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return HasMany<CustomerActivityLog>
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(CustomerActivityLog::class);
    }
}

