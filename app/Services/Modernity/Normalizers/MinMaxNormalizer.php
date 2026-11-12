<?php

namespace App\Services\Modernity\Normalizers;

use App\Contracts\Modernity\ModernityVectorNormalizer;
use App\Data\Analysis\ModernityVector;

/**
 * Rescales the vector, such that its values lie between 0.0 and 1.0 (inclusive). 0.0 represents the vector's original
 * minimal value, 1.0 represents the vector's original maximal value, and all values in between are scaled to ratio.
 *
 * There is one catch to this normalization. When all values are equal, the above description would assign them all the
 * normalized value 0.0 OR the normalized value 1.0, but that is impossible. In this edge case, the normalizer 'clips'
 * the values, e.g.:
 *
 * <ul>
 *     <li><code>[-2.0, ...]</code> is clipped to <code>[0.0, ...]</code></li>
 *     <li><code>[2.0, ...]</code> is clipped to <code>[1.0, ...]</code></li>
 *     <li><code>[0.0, ...]</code> remains unchanged.</li>
 *     <li><code>[1.0, ...]</code> remains unchanged.</li>
 * </ul>
 */
final class MinMaxNormalizer implements ModernityVectorNormalizer
{
    public function normalize(ModernityVector $vector): ModernityVector
    {
        $min = $vector->min();
        $max = $vector->max();
        $range = $max - $min;

        if ($range === 0.0) {
            return $vector->map(fn () => max(min(1.0, $max), 0.0));
        }

        return $vector->map(fn (float $val): float => ($val - $min) / $range);
    }
}
