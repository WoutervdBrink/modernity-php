<?php

namespace App\Contracts\Modernity;

use App\Data\Analysis\Turbo\TurboDocument;

interface ModernityAnalyzer
{
    public function analyze(string $code): TurboDocument;
}
