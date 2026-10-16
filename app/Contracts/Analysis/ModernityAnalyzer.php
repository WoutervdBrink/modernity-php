<?php

namespace App\Contracts\Analysis;

use App\Data\Analysis\Turbo\TurboDocument;

interface ModernityAnalyzer
{
    public function analyze(string $code): TurboDocument;
}
