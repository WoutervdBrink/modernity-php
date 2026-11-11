<?php

namespace App\Data\Snapshot;

use App\Data\Repository\RepositoryData;
use App\Models\Enums\ProcessStatus;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class SnapshotData extends Data
{
    public function __construct(
        public int $id,
        public string $tag,
        public string $commit_sha,
        public ?string $semver,
        public ProcessStatus $analysis_status,
        public ?CarbonImmutable $analyzed_at,
        public ?CarbonImmutable $created_at,
        public ?CarbonImmutable $updated_at,
        public Optional|RepositoryData $repository,
    ) {}
}
