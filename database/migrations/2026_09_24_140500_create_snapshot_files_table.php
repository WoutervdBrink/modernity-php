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
        Schema::create('snapshot_files', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('snapshot_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('path');
            $table->char('sha256', 64);
            $table->foreign('sha256')
                ->references('sha256')
                ->on('source_files')
                ->restrictOnDelete();
            $table->timestamps();

            $table->unique(['snapshot_id', 'path']);
            $table->index('sha256');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('snapshot_files');
    }
};
