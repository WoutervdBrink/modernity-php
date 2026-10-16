<?php

namespace App\Data\SourceFile;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

final class SourceFileData extends Data
{
    public function __construct(
        public string $sha256,
        public int $size,
        public CarbonImmutable $created_at,
        public CarbonImmutable $updated_at,
    ) {}
}
