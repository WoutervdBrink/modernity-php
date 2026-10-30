<?php

namespace App\Jobs;

use App\Actions\Repository\QueueSnapshotDiscovery;
use App\Actions\Search\QueueSearch;
use App\Actions\Snapshot\QueueSnapshotAnalysis;
use App\Models\Enums\ProcessStatus;
use App\Models\Repository;
use App\Models\Search;
use App\Models\Snapshot;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Tries;
use Throwable;

#[Tries(1)]
final class QueuePendingProcesses implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public ProcessStatus $processStatus)
    {
        //
    }

    public function uniqueKey(): string
    {
        return $this->processStatus->value;
    }

    /**
     * Execute the job.
     *
     * @throws Throwable
     */
    public function handle(QueueSearch $queueSearch,
        QueueSnapshotDiscovery $queueSnapshotDiscovery,
        QueueSnapshotAnalysis $queueSnapshotAnalysis): void
    {
        Search::query()
            ->whereStatus($this->processStatus)
            ->get()
            ->each(function (Search $search) use ($queueSearch): void {
                $queueSearch($search);
            });

        Repository::query()
            ->accepted(true)
            ->whereSnapshotDiscoveryStatus($this->processStatus)
            ->get()
            ->each(function (Repository $repository) use ($queueSnapshotDiscovery): void {
                $queueSnapshotDiscovery($repository);
            });

        Snapshot::query()
            ->whereNotNull('semver')
            ->whereAnalysisStatus($this->processStatus)
            ->get()
            ->each(function (Snapshot $snapshot) use ($queueSnapshotAnalysis): void {
                $queueSnapshotAnalysis($snapshot);
            });
    }
}
