<?php

namespace App\Data\SourceFile;

use App\Data\Snapshot\SnapshotData;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class SourceFileData extends Data
{
    public function __construct(
        public string $sha256,
        public int $size,
        public ?string $exception,
        public bool $is_analyzed,
        public bool $has_thrown_exception,
        public ?CarbonImmutable $created_at,
        public ?CarbonImmutable $updated_at,
        public Optional|SnapshotData $snapshot,
    ) {}
}
