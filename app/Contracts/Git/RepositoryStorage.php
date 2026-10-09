<?php

namespace App\Contracts\Git;

use App\Data\Git\GitTag;
use App\Models\Repository;
use Illuminate\Support\Collection;

interface RepositoryStorage
{
    public function ensureCloned(Repository $repository): void;

    public function fetch(Repository $repository, bool $force = false): void;

    /**
     * @return Collection<int, GitTag>
     */
    public function discoverTags(Repository $repository): Collection;

    public function hasCommit(Repository $repository, string $sha): bool;

    public function archive(Repository $repository, string $sha): void;
}
