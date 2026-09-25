<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('search_results', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('search_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('repository_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('rejection_reason')
                ->nullable();
            $table->json('observed_data');
            $table->timestamp('discovered_at');
            $table->timestamps();

            $table->unique(['search_id', 'repository_id']);
            $table->index('repository_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_results');
    }
};
