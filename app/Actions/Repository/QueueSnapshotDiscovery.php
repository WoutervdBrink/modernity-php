<?php

namespace App\Actions\Repository;

use App\Jobs\DiscoverSnapshots as DiscoverSnapshotsJob;
use App\Models\Repository;
use Illuminate\Support\Facades\DB;
use Throwable;

final class QueueSnapshotDiscovery
{
    /**
     * @throws Throwable
     */
    public function __invoke(Repository $repository): void
    {
        DB::transaction(function () use ($repository): void {
            $repository = Repository::query()
                ->lockForUpdate()
                ->findOrFail($repository->id);

            $repository->setProcessQueued();

            DiscoverSnapshotsJob::dispatch($repository)->afterCommit();
        });
    }
}
