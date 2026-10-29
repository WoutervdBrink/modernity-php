<?php

use App\Models\Enums\ProcessStatus;
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
        Schema::table('snapshots', function (Blueprint $table): void {
            $table->dropColumn('committed_at');
            $table->dropColumn('downloaded_at');
            $table->dropColumn('indexed_at');

            $table
                ->timestamp('analyzed_at')
                ->nullable();

            $table->string('analysis_status')
                ->default(ProcessStatus::PENDING)
                ->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('snapshots', function (Blueprint $table): void {
            $table->dropColumn('analysis_status');
            $table->dropColumn('analyzed_at');

            $table->timestamp('committed_at')
                ->nullable();
            $table->timestamp('downloaded_at')
                ->nullable();
            $table->timestamp('indexed_at')
                ->nullable();
        });
    }
};
