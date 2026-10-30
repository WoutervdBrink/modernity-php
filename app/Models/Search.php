<?php

namespace App\Models;

use App\Data\GitHub\GitHubRepository;
use App\Data\Search\SearchParameters;
use App\Models\Concerns\HasProcessStatus;
use App\Models\Enums\ProcessStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Search extends Model
{
    use HasProcessStatus;

    public static string $processStatusColumn = 'status';

    public static string $processCompletedAtColumn = 'finished_at';

    protected $casts = [
        'parameters' => SearchParameters::class,
        'status' => ProcessStatus::class,
        'started_at' => 'immutable_datetime',
        'finished_at' => 'immutable_datetime',
    ];

    public static function create(SearchParameters $parameters, string $applicationCommit): self
    {
        $search = new self;

        $search->parameters = $parameters;
        $search->application_commit = $applicationCommit;
        $search->setProcessPending();

        $search->save();

        return $search;
    }

    public function associateCandidateRepository(Repository $repository, GitHubRepository $observedData, ?string $rejectionReason): SearchResult
    {
        $result = new SearchResult;
        $result->search()->associate($this);
        $result->repository()->associate($repository);
        $result->rejection_reason = $rejectionReason;
        $result->observed_data = $observedData;
        $result->discovered_at = now();
        $result->save();

        return $result;
    }

    /**
     * @return HasMany<SearchResult, $this>
     */
    public function results(): HasMany
    {
        return $this->hasMany(SearchResult::class)->latest('discovered_at');
    }

    /**
     * @return HasMany<SearchResult, $this>
     */
    public function acceptedResults(): HasMany
    {
        return $this->hasMany(SearchResult::class)
            ->whereNull('rejection_reason');
    }
}
