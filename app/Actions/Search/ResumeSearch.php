<?php

namespace App\Actions\Search;

use App\Jobs\DoSearch;
use App\Models\Search;
use Throwable;

final class ResumeSearch
{
    /**
     * @throws Throwable
     */
    public function __invoke(Search $search): void
    {
        DoSearch::dispatch($search)->afterCommit();
    }
}
