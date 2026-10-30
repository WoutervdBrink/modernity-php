<?php

namespace App\Jobs;

use App\Contracts\Git\RepositoryStorage;
use App\Data\Git\GitTag;
use App\Models\Enums\ProcessStatus;
use App\Models\Repository;
use CzProject\GitPhp\GitException;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\DeleteWhenMissingModels;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\Attributes\Tries;
use Illuminate\Support\Facades\DB;
use Throwable;

#[Tries(1)]
#[DeleteWhenMissingModels]
#[Timeout(3600)]
final class DiscoverSnapshots implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Repository $repository) {}

    /**
     * Execute the job.
     *
     * @throws GitException
     * @throws Throwable
     */
    public function handle(RepositoryStorage $storage): void
    {
        $this->repository->setProcessRunning();

        $storage->fetch($this->repository);

        $tags = $storage->discoverTags($this->repository);

        DB::transaction(function () use ($tags): void {
            $tags->each(function (GitTag $tag): void {
                $this->repository->recordSnapshot($tag);
            });

            $this->repository->setProcessCompleted();
        });
    }

    public function failed(): void
    {
        if ($this->repository->exists() && $this->repository->snapshot_discovery_status === ProcessStatus::RUNNING) {
            $this->repository->setProcessFailed();
        }
    }

    public function uniqueId(): string
    {
        return (string) $this->repository->id;
    }
}
