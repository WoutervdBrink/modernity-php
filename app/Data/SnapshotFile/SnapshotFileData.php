<?php

namespace App\Data\SnapshotFile;

use App\Data\SourceFile\SourceFileData;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

final class SnapshotFileData extends Data
{
    public function __construct(
        public int $id,
        public string $path,
        public string $sha256,
        public SourceFileData $sourceFile,
        public CarbonImmutable $created_at,
        public CarbonImmutable $updated_at,
    ) {}
}
