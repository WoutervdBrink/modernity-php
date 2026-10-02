<?php

namespace App\Actions\Search;

use App\Data\Search\SearchParameters;
use App\Jobs\DoSearch;
use App\Models\Search;
use Illuminate\Support\Facades\DB;

final class CreateSearch
{
    public function __invoke(SearchParameters $parameters): Search
    {
        return DB::transaction(function () use ($parameters): Search {
            $search = Search::create($parameters);

            DoSearch::dispatch($search)->afterCommit();

            return $search;
        });
    }
}
