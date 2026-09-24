<?php

namespace App\Turbo\Data;

use Spatie\LaravelData\Data;

final class TurboRange extends Data
{
    public function __construct(
        public ?int $min,
        public ?int $max,
    ) {}
}
