<?php

namespace App\Data\Snapshot;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

final class SnapshotData extends Data
{
    public function __construct(
        public int $id,
        public string $tag,
        public string $commit_sha,
        public ?string $semver,
        public ?CarbonImmutable $commited_at,
        public ?CarbonImmutable $downloaded_at,
        public ?CarbonImmutable $indexed_at,
        public ?CarbonImmutable $created_at,
        public ?CarbonImmutable $updated_at,
    ) {}
}
