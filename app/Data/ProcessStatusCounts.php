<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

final class ProcessStatusCounts extends Data
{
    #[Computed]
    public int $total;

    public function __construct(
        public int $pending = 0,
        public int $queued = 0,
        public int $running = 0,
        public int $completed = 0,
        public int $failed = 0,
    ) {
        $this->total = $pending + $queued + $running + $completed + $failed;
    }
}
