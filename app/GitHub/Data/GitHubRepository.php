<?php

namespace App\GitHub\Data;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

final class GitHubRepository extends Data
{
    public function __construct(
        public int $id,
        public string $full_name,
        public string $description,
        #[WithCast(DateTimeInterfaceCast::class)]
        public CarbonImmutable $created_at,
        #[WithCast(DateTimeInterfaceCast::class)]
        public CarbonImmutable $updated_at,
        public int $stargazers_count,
    ) {}
}
