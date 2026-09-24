<?php

namespace App\Turbo\Data;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

final class TurboRangesCastAndTransformer implements Cast, Transformer
{
    /**
     * @return TurboRange[]
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): array
    {
        if (! is_array($value)) {
            return [];
        }

        $ranges = [];

        foreach ($value as $range) {
            if (! is_array($range) || ! is_int($range[0]) || ! is_int($range[1])) {
                continue;
            }

            $ranges[] = new TurboRange($range[0], $range[1]);
        }

        return $ranges;
    }

    /**
     * @return array<int, array{0: ?int, 1: ?int}>
     */
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): array
    {
        if (! is_array($value)) {
            return [];
        }

        /**
         * @var array<int, array{0: ?int, 1: ?int}> $transformed
         */
        $transformed = [];

        foreach ($value as $range) {
            if (! $range instanceof TurboRange) {
                continue;
            }

            $transformed[] = [$range->min, $range->max];
        }

        return $transformed;
    }
}
