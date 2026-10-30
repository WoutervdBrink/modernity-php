<?php

namespace App\Data\Playground;

use App\Data\Analysis\Turbo\TurboDocument;
use Spatie\LaravelData\Data;

final class AnalyzePlaygroundSourceResponseData extends Data
{
    public function __construct(
        public ?TurboDocument $analysis,
        public ?string $error,
    ) {}
}
