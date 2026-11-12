<?php

namespace App\Data\Analysis;

use Ds\Vector;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

final class VectorCastAndTransformer implements Cast, Transformer
{
    /**
     * @return Vector<float>
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): Vector
    {
        if (! is_array($value)) {
            return new Vector;
        }

        $vec = new Vector;

        foreach ($value as $item) {
            if (! is_numeric($item)) {
                continue;
            }
            $vec->push(floatval($item));
        }

        return $vec;
    }

    /**
     * @return array<float>
     */
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): array
    {
        if (! $value instanceof Vector) {
            return [];
        }

        return $value->toArray();
    }
}
