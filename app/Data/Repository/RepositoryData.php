<?php

namespace App\Data\Repository;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

final class RepositoryData extends Data
{
    public function __construct(
        public int $id,
        public int $github_id,
        public string $name,
        public ?string $description,
        public ?CarbonImmutable $snapshots_discovered_at,
        public ?CarbonImmutable $created_at,
        public ?CarbonImmutable $updated_at,
    ) {}
}
