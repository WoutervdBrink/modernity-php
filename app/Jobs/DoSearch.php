<?php

namespace App\Jobs;

use App\Actions\Repository\QueueSnapshotDiscovery;
use App\Data\GitHub\GitHubRepository;
use App\Models\Enums\SearchStatus;
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
use Illuminate\Queue\Attributes\Tries;
use Throwable;

#[Tries(1)]
#[DeleteWhenMissingModels]
final class DoSearch implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Search $search) {}

    public function uniqueId(): string
    {
        return (string) $this->search->getKey();
    }

    /**
     * Execute the job.
     */
    public function handle(GitHubManager $gh, QueueSnapshotDiscovery $queueSnapshotDiscovery): void
    {
        $this->search->start();

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

                $queueSnapshotDiscovery($result->repository);
            }

            if ($accepted >= $this->search->parameters->max) {
                break;
            }
        }

        $this->search->complete();
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
        $languageInfo = $client->repos()->languages($name[0], $name[1]);
        $candidate->phpShare = (($languageInfo['PHP'] ?? 0) / array_sum($languageInfo)) * 100;
        if ($candidate->phpShare < $this->search->parameters->php) {
            return 'PHP share is '.round($candidate->phpShare, 2).'%; minimum is '.$this->search->parameters->php.'%';
        }

        return null;
    }

    public function failed(?Throwable $exception): void
    {
        $search = Search::find($this->search->getKey());

        if ($search?->status === SearchStatus::RUNNING) {
            $search->fail();
        }
    }
}
