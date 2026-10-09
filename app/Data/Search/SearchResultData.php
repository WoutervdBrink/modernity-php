<?php

namespace App\Data\Search;

use App\Data\GitHub\GitHubRepository;
use App\Data\Repository\RepositoryData;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

final class SearchResultData extends Data
{
    public function __construct(
        public int $id,
        public RepositoryData $repository,
        public ?string $rejection_reason,
        public GitHubRepository $observedData,
        public ?CarbonImmutable $discovered_at,
        public ?CarbonImmutable $created_at,
        public CarbonImmutable $updated_at,
    ) {}
}
