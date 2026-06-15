<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class AIInsight extends Model
{
    protected $table = 'ai_insights';

    protected $fillable = [
        'user_id',
        'query_hash',
        'query',
        'retrieved_context',
        'insight',
        'provider',
        'latency_ms',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'retrieved_context' => 'array',
            'metadata' => 'array',
        ];
    }
}

