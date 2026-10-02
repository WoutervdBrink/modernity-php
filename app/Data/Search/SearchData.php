<?php

namespace App\Data\Search;

use App\Models\Enums\SearchStatus;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\AutoWhenLoadedLazy;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

final class SearchData extends Data
{
    /**
     * @param  Lazy|Collection<int, SearchResultData>  $results
     */
    public function __construct(
        public int $id,
        public SearchParameters $parameters,
        public SearchStatus $status,
        public string $application_commit,
        public ?CarbonImmutable $started_at,
        public ?CarbonImmutable $finished_at,
        public CarbonImmutable $created_at,
        public CarbonImmutable $updated_at,
        #[AutoWhenLoadedLazy()]
        public Lazy|Collection $results,
        public ?int $results_count,
        public ?int $acceptedResults_count,
    ) {}
}
