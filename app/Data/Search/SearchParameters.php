<?php

namespace App\Data\Search;

use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\GreaterThanOrEqualTo;
use Spatie\LaravelData\Attributes\Validation\LessThanOrEqualTo;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class SearchParameters extends Data
{
    public function __construct(
        #[Date]
        public string|Optional|null $cutoff,
        #[GreaterThanOrEqualTo(0)]
        #[LessThanOrEqualTo(100)]
        public int|Optional $php = 0,
        public int|Optional $max = 100
    ) {}
}
