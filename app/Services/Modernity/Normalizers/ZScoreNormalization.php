<?php

namespace App\Services\Modernity\Normalizers;

use App\Contracts\Modernity\ModernityVectorNormalizer;
use App\Data\Analysis\ModernityVector;

final class ZScoreNormalization implements ModernityVectorNormalizer
{
    public function normalize(ModernityVector $vector): ModernityVector
    {
        $values = $vector->values();
        $N = (float) $vector->versions->count();
        $mean = array_sum($values) / $N;
        $stdDev = sqrt(array_reduce($values, fn (float $sum, float $value): float => pow($value - $mean, 2), 0.0) / $N);

        return $vector->map(fn (float $val): float => ($val - $mean) / ($stdDev === 0.0 ? 1.0 : $stdDev));
    }
}
