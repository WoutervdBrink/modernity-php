<?php

namespace App\Jobs;

use App\Contracts\Git\RepositoryStorage;
use App\Data\Git\GitTag;
use App\Models\Repository;
use CzProject\GitPhp\GitException;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\DeleteWhenMissingModels;
use Illuminate\Queue\Attributes\Tries;
use Illuminate\Support\Facades\DB;
use Throwable;

#[Tries(1)]
#[DeleteWhenMissingModels]
final class DiscoverSnapshots implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Repository $repository) {}

    public function uniqueId(): string
    {
        return (string) $this->repository->id;
    }

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

    public function failed(?Throwable $exception): void
    {
        $repository = Repository::find($this->repository->id);

        if ($repository === null) {
            return;
        }

        $repository->setProcessFailed();
    }
}
