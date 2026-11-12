<?php

namespace App\Services\Modernity\Normalizers;

use App\Contracts\Modernity\ModernityVectorNormalizer;
use App\Data\Analysis\ModernityVector;

/**
 * Uses <code>log1p</code> rather than <code>log</code>, because <code>log</code> will not work for zero values, but
 * modernity vectors might contain lots of zeroes.
 */
final class LogNormalization implements ModernityVectorNormalizer
{
    public function normalize(ModernityVector $vector): ModernityVector
    {
        $logs = array_map(log1p(...), $vector->values());
        $divisor = array_sum($logs);

        return $vector->map(fn (float $val): float => log1p($val) / $divisor);
    }
}
