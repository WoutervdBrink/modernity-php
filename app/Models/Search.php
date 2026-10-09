<?php

namespace App\Models;

use App\Data\GitHub\GitHubRepository;
use App\Data\Search\SearchParameters;
use App\Models\Enums\SearchStatus;
use App\Services\Git\LocalRepositoryStorage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LogicException;

final class Search extends Model
{
    public static function create(SearchParameters $parameters): self
    {
        $search = new self;

        $search->parameters = $parameters;
        $search->status = SearchStatus::PENDING;
        $search->application_commit = LocalRepositoryStorage::getCurrentApplicationCommitHash() ?? str_repeat('0', 40);

        $search->save();

        return $search;
    }

    public function lock(): self
    {
        return self::query()->lockForUpdate()->findOrFail($this->id);
    }

    public function start(): void
    {
        $this->transitionTo(SearchStatus::RUNNING);
        $this->started_at ??= now();
        $this->save();
    }

    private function transitionTo(SearchStatus $status): void
    {
        if ($this->status === $status) {
            return;
        }

        if (! $this->status->isTransitionAllowed($status)) {
            throw new LogicException('Transitioning from status '.$this->status->name.' to '.$status->value.' is not allowed.');
        }

        $this->status = $status;
    }

    public function complete(): void
    {
        $this->transitionTo(SearchStatus::COMPLETED);
        $this->finished_at = now();
        $this->save();
    }

    public function fail(): void
    {
        $this->transitionTo(SearchStatus::FAILED);
        $this->save();
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
        return $this->hasMany(SearchResult::class)->orderBy('discovered_at', 'desc');
    }

    public function acceptedResults(): HasMany
    {
        return $this->hasMany(SearchResult::class)
            ->whereNull('rejection_reason');
    }

    protected function casts(): array
    {
        return [
            'parameters' => SearchParameters::class,
            'status' => SearchStatus::class,
            'started_at' => 'immutable_datetime',
            'finished_at' => 'immutable_datetime',
        ];
    }
}
