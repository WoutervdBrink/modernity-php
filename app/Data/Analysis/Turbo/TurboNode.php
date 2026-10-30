<?php

namespace App\Data\Analysis\Turbo;

use Spatie\LaravelData\Attributes\WithCastAndTransformer;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;

final class TurboNode extends Data
{
    /**
     * @param  array<int, TurboRange>  $ranges
     * @param  array<int, TurboNode>  $children
     */
    public function __construct(
        public int $type,
        #[WithCastAndTransformer(TurboRangesCastAndTransformer::class)]
        #[LiteralTypeScriptType('[number | null, number | null][]')]
        public array $ranges,
        public array $children,
    ) {}
}
