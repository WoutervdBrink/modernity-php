<?php

namespace App\Contracts\Modernity;

use App\Data\Analysis\ModernityVector;

interface ModernityVectorNormalizer
{
    public function normalize(ModernityVector $vector): ModernityVector;
}
