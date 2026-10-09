<?php

use App\Models\Enums\RepositorySnapshotDiscoveryStatus;
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
        Schema::table('repositories', function (Blueprint $table): void {
            $table->string('snapshot_discovery_status')
                ->default(RepositorySnapshotDiscoveryStatus::PENDING);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repositories', function (Blueprint $table): void {
            $table->dropColumn('snapshot_discovery_status');
        });
    }
};
