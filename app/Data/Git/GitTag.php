<?php

namespace App\Data\Git;

use Composer\Semver\VersionParser;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;
use UnexpectedValueException;

final class GitTag extends Data
{
    #[Computed]
    public ?string $semver;

    public function __construct(
        public string $name,
        public string $sha,
    ) {
        try {
            $this->semver = new VersionParser()->normalize($this->name);
        } catch (UnexpectedValueException $e) {
            $this->semver = null;
        }
    }
}
