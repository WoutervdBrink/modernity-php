<?php

namespace App\Jobs;

use App\Actions\Repository\QueueSnapshotDiscovery;
use App\Data\GitHub\GitHubRepository;
use App\Models\Enums\ProcessStatus;
use App\Models\Repository;
use App\Models\Search;
use App\Models\SearchResult;
use Github\Client;
use Github\ResultPager;
use GrahamCampbell\GitHub\GitHubManager;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\DeleteWhenMissingModels;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\Attributes\Tries;
use Throwable;

#[Tries(1)]
#[DeleteWhenMissingModels]
#[Timeout(3600)]
final class DoSearch implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Search $search) {}

    /**
     * Execute the job.
     *
     * @throws Throwable
     */
    public function handle(GitHubManager $gh, QueueSnapshotDiscovery $queueSnapshotDiscovery): void
    {
        $this->search->started_at = now()->toImmutable();
        $this->search->setProcessRunning();

        $client = $gh->connection();
        $pager = new ResultPager($client, 100);

        $repositories = $pager->fetchAllLazy(
            $client->search(),
            'repositories',
            [$this->buildSearchQuery(), 'stars']
        );

        $accepted = 0;

        foreach ($repositories as $repository) {
            $candidate = GitHubRepository::from($repository);

            $result = $this->consider($client, $candidate);

            if (! $result->is_rejected) {
                $accepted++;

                if ($result->repository !== null) {
                    $queueSnapshotDiscovery($result->repository);
                }
            }

            if ($accepted >= $this->search->parameters->max) {
                break;
            }
        }

        $this->search->setProcessCompleted();
    }

    private function buildSearchQuery(): string
    {
        $query = 'language:php';

        if (is_string($this->search->parameters->cutoff)) {
            $query .= ' created:<='.$this->search->parameters->cutoff;
        }

        return $query;
    }

    private function consider(Client $client, GitHubRepository $candidate): SearchResult
    {
        $repository = Repository::fromGitHubRepository($candidate);
        $rejectionReason = $this->determineRejectionReason($client, $candidate);

        return $this->search->associateCandidateRepository($repository, $candidate, $rejectionReason);
    }

    private function determineRejectionReason(Client $client, GitHubRepository $candidate): ?string
    {
        $name = explode('/', $candidate->full_name, 2);
        /** @var array<string, int> $languageInfo */
        $languageInfo = $client->repos()->languages($name[0], $name[1]);
        $phpBytes = (float) ($languageInfo['PHP'] ?? 0);
        $totalBytes = (float) array_sum($languageInfo);
        $candidate->phpShare = $phpBytes / $totalBytes * 100.0;
        if ($candidate->phpShare < $this->search->parameters->php) {
            return sprintf('PHP share is %.2f%%; minimum is %d%%', $candidate->phpShare, $this->search->parameters->php);
        }

        return null;
    }

    public function failed(): void
    {
        if ($this->search->exists() && $this->search->status === ProcessStatus::RUNNING) {
            $this->search->setProcessFailed();
        }
    }

    public function uniqueId(): string
    {
        return (string) $this->search->id;
    }
}
