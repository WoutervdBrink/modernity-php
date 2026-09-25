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
        Schema::create('snapshots', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('repository_id')
                ->constrained('repositories')
                ->cascadeOnDelete();
            $table->string('tag');
            $table->char('commit_sha', 40);
            $table->timestamp('committed_at')
                ->nullable();
            $table->timestamp('downloaded_at')
                ->nullable();
            $table->timestamp('indexed_at')
                ->nullable();
            $table->timestamps();

            $table->unique(['repository_id', 'tag']);
            $table->index(['repository_id', 'commit_sha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('snapshots');
    }
};
