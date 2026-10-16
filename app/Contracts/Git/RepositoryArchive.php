<?php

namespace App\Contracts\Git;

use App\Data\Analysis\RepositoryFile;
use Generator;

interface RepositoryArchive
{
    /**
     * @return Generator<RepositoryFile>
     */
    public function phpFiles(): Generator;

    public function destroy(): void;
}
