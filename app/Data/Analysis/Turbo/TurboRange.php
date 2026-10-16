<?php

namespace App\Data\Analysis\Turbo;

use Spatie\LaravelData\Data;

final class TurboRange extends Data
{
    public function __construct(
        public ?int $min,
        public ?int $max,
    ) {}
}
