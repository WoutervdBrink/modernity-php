<?php

namespace App\Services\Modernity\Normalizers;

use App\Contracts\Modernity\ModernityVectorNormalizer;
use App\Data\Analysis\ModernityVector;

final class MaxNormalizer implements ModernityVectorNormalizer
{
    public function normalize(ModernityVector $vector): ModernityVector
    {
        $max = $vector->max();

        return $vector->map(fn (float $val): float => $max === 0.0 ? 0.0 : $val / $max);
    }
}
