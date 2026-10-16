<?php

namespace App\Data;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

final class FailedJobData extends Data
{
    public function __construct(
        public int $id,
        public string $uuid,
        public string $connection,
        public string $queue,
        public string $payload,
        public string $exception,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i:s')]
        public CarbonImmutable $failed_at,
    ) {}
}
