<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('category');
            $table->string('type')->default('standard');
            $table->text('description')->nullable();
            $table->decimal('base_price', 14, 2);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('delivery_sla_hours')->default(72);
            $table->json('metadata')->nullable();
            $table->timestampsTz();

            $table->index(['is_active', 'category']);
            $table->index(['type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
