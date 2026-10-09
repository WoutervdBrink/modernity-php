<?php

namespace App\Actions\Repository;

use App\Jobs\DiscoverSnapshots as DiscoverSnapshotsJob;
use App\Models\Repository;
use Illuminate\Support\Facades\DB;

final class QueueSnapshotDiscovery
{
    public function __invoke(Repository $repository): void
    {
        DB::transaction(function () use ($repository): void {
            $repository = Repository::query()
                ->lockForUpdate()
                ->findOrFail($repository->id);

            $repository->queueSnapshotDiscovery();

            DiscoverSnapshotsJob::dispatch($repository)->afterCommit();
        });
    }
}
