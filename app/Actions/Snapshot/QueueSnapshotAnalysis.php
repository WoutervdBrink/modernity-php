<?php

namespace App\Actions\Snapshot;

use App\Jobs\AnalyzeSnapshot;
use App\Models\Snapshot;
use Illuminate\Support\Facades\DB;
use Throwable;

final class QueueSnapshotAnalysis
{
    /**
     * @throws Throwable
     */
    public function __invoke(Snapshot $snapshot): void
    {
        DB::transaction(function () use ($snapshot): void {
            $snapshot = Snapshot::query()
                ->lockForUpdate()
                ->findOrFail($snapshot->id);

            $snapshot->setProcessQueued();

            AnalyzeSnapshot::dispatch($snapshot)->afterCommit();
        });
    }
}
