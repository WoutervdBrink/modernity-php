<?php

namespace App\Turbo\Data;

use Spatie\LaravelData\Attributes\WithCastAndTransformer;
use Spatie\LaravelData\Data;

final class TurboNode extends Data
{
    /**
     * @param  array<int, TurboRange>  $ranges
     * @param  array<int, TurboNode>  $children
     */
    public function __construct(
        public int $type,
        #[WithCastAndTransformer(TurboRangesCastAndTransformer::class)]
        public array $ranges,
        public array $children,
    ) {}
}
