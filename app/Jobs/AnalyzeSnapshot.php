<?php

namespace App\Jobs;

use App\Actions\Analysis\AnalyzeSourceFile;
use App\Contracts\Git\RepositoryStorage;
use App\Models\Enums\ProcessStatus;
use App\Models\Repository;
use App\Models\Snapshot;
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
final class AnalyzeSnapshot implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Snapshot $snapshot) {}

    /**
     * Execute the job.
     *
     * @throws Throwable
     */
    public function handle(RepositoryStorage $storage, AnalyzeSourceFile $analyze): void
    {
        $this->snapshot->setProcessRunning();

        /** @var Repository $repository */
        $repository = $this->snapshot->repository;

        $archive = $storage->archive($repository, $this->snapshot->commit_sha);

        try {
            DB::transaction(function () use ($archive, $analyze): void {
                foreach ($archive->phpFiles() as $file) {
                    $sourceFile = $analyze($file);

                    $this->snapshot->recordFile($file->path, $sourceFile);
                }

                $this->snapshot->setProcessCompleted();
            });
        } finally {
            $archive->destroy();
        }
    }

    public function failed(): void
    {
        if ($this->snapshot->exists() && $this->snapshot->analysis_status === ProcessStatus::RUNNING) {
            $this->snapshot->setProcessFailed();
        }
    }

    public function uniqueId(): string
    {
        return (string) $this->snapshot->id;
    }
}
