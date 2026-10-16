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
            $table->text('contents')->change();
            $table->text('analysis')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('source_files', function (Blueprint $table): void {
            $table->binary('contents')->change();
            $table->binary('analysis')->nullable()->change();
        });
    }
};
