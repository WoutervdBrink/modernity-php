<?php

namespace App\Jobs;

use App\Data\Repository\GitHubRepository;
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
use Illuminate\Queue\Attributes\Tries;
use Throwable;

#[Tries(1)]
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
    public function handle(GitHubManager $gh): void
    {
        $search = $this->search->fresh();

        $search->start();

        $client = $gh->connection();
        $pager = new ResultPager($client, 100);

        $repositories = $pager->fetchAllLazy(
            $client->search(),
            'repositories',
            [$this->buildSearchQuery($search), 'stars']
        );

        $accepted = 0;

        foreach ($repositories as $repository) {
            $candidate = GitHubRepository::from($repository);

            $result = $this->consider($client, $search, $candidate);

            if (! $result->is_rejected) {
                $accepted++;
            }

            if ($accepted >= $search->parameters->max) {
                break;
            }
        }

        $search->complete();
    }

    private function buildSearchQuery(Search $search): string
    {
        $query = 'language:php';

        if (is_string($search->parameters->cutoff)) {
            $query .= ' created:<='.$this->search->parameters->cutoff;
        }

        return $query;
    }

    private function consider(Client $client, $search, GitHubRepository $candidate): SearchResult
    {
        $repository = Repository::fromGitHubRepository($candidate);
        $rejectionReason = $this->determineRejectionReason($client, $search, $candidate);

        return $search->associateCandidateRepository($repository, $candidate, $rejectionReason);
    }

    private function determineRejectionReason(Client $client, Search $search, GitHubRepository $candidate): ?string
    {
        $name = explode('/', $candidate->full_name, 2);
        $languageInfo = $client->repos()->languages($name[0], $name[1]);
        $candidate->phpShare = (($languageInfo['PHP'] ?? 0) / array_sum($languageInfo)) * 100;
        if ($candidate->phpShare < $search->parameters->php) {
            return 'PHP share is '.round($candidate->phpShare, 2).'%; minimum is '.$search->parameters->php.'%';
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
