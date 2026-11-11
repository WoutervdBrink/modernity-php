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
        Schema::table('source_files', function (Blueprint $table): void {
            $table->enum('content_encoding', ['plain', 'base64'])
                ->default('plain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('source_files', function (Blueprint $table): void {
            $table->dropColumn('content_encoding');
        });
    }
};
