<?php

namespace App\Data\Repository;

use App\Models\Enums\RepositorySnapshotDiscoveryStatus;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class RepositoryData extends Data
{
    public function __construct(
        public int $id,
        public int $github_id,
        public string $name,
        public ?string $description,
        public bool $is_accepted,
        public RepositorySnapshotDiscoveryStatus $snapshot_discovery_status,
        public Optional|bool $is_discovering_snapshots,
        public ?CarbonImmutable $fetched_at,
        public ?CarbonImmutable $snapshots_discovered_at,
        public ?CarbonImmutable $created_at,
        public ?CarbonImmutable $updated_at,
    ) {}
}
