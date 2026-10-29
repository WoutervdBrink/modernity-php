<?php

namespace App\Data\Analysis;

use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

final class RepositoryFile extends Data
{
    /**
     * @var int<0, max>
     */
    #[Computed]
    public int $size;

    #[Computed]
    public string $sha256;

    public function __construct(
        public string $path,
        public string $contents,
    ) {
        $this->size = strlen($this->contents);
        $this->sha256 = hash('sha256', $this->contents);
    }
}
