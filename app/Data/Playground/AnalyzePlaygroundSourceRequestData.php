<?php

namespace App\Data\Playground;

use Spatie\LaravelData\Data;

final class AnalyzePlaygroundSourceRequestData extends Data
{
    public function __construct(
        public string $source,
    ) {}
}
