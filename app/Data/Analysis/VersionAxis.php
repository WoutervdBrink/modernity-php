<?php

namespace App\Data\Analysis;

use Spatie\LaravelData\Data;

final class VersionAxis extends Data
{
    /**
     * @param  array<string>  $versions
     */
    public function __construct(
        public readonly array $versions,
    ) {}

    public function equals(VersionAxis $other): bool
    {
        return $this->versions === $other->versions;
    }

    /**
     * @return int<0, max>
     */
    public function count(): int
    {
        return count($this->versions);
    }
}
