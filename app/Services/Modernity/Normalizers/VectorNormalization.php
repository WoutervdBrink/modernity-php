<?php

namespace App\Services\Modernity\Normalizers;

use App\Contracts\Modernity\ModernityVectorNormalizer;
use App\Data\Analysis\ModernityVector;

final class VectorNormalization implements ModernityVectorNormalizer
{
    public function normalize(ModernityVector $vector): ModernityVector
    {
        $next = $vector->values();
        $distance = sqrt(array_reduce($next, fn (float $sum, float $val): float => $sum + $val * $val, 0.0));

        return $vector->map(fn (float $val): float => $distance === 0.0 ? $val : $val / $distance);
    }
}
