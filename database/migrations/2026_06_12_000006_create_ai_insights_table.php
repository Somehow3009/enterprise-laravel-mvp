<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_insights', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('query_hash', 64)->index();
            $table->text('query');
            $table->json('retrieved_context')->nullable();
            $table->longText('insight');
            $table->string('provider')->default('mock');
            $table->unsignedInteger('latency_ms')->nullable();
            $table->json('metadata')->nullable();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_insights');
    }
};
