<?php

namespace App\Data\SnapshotFile;

use App\Data\Snapshot\SnapshotData;
use App\Data\SourceFile\SourceFileData;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class SnapshotFileData extends Data
{
    public function __construct(
        public int $id,
        public string $path,
        public string $sha256,
        public Optional|SourceFileData $sourceFile,
        public Optional|SnapshotData $snapshot,
        public CarbonImmutable $created_at,
        public CarbonImmutable $updated_at,
    ) {}
}
