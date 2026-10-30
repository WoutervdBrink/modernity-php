<?php

namespace App\Actions\Search;

use App\Jobs\DoSearch;
use App\Models\Search;
use Illuminate\Support\Facades\DB;
use Throwable;

final class QueueSearch
{
    /**
     * @throws Throwable
     */
    public function __invoke(Search $search): void
    {
        DB::transaction(function () use ($search): void {
            $search = Search::query()
                ->lockForUpdate()
                ->findOrFail($search->id);

            $search->setProcessQueued();

            DoSearch::dispatch($search)->afterCommit();
        });
    }
}
