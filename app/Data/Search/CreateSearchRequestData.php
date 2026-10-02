<?php

namespace App\Data\Search;

use Spatie\LaravelData\Data;

final class CreateSearchRequestData extends Data
{
    public function __construct(
        public SearchParameters $parameters,
    ) {
        //
    }
}
