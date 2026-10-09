<?php

namespace App\Services\Git;

use App\Contracts\Git\RepositoryStorage;
use App\Data\Git\GitTag;
use App\Models\Repository;
use CzProject\GitPhp\Git;
use CzProject\GitPhp\GitException;
use CzProject\GitPhp\GitRepository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

final class LocalRepositoryStorage implements RepositoryStorage
{
    private Filesystem $disk;

    public function __construct(private Git $git)
    {
        $this->disk = Storage::disk('repositories');
    }

    public static function getCurrentApplicationCommitHash(): ?string
    {
        $dotGit = base_path('.git/');

        if (! is_dir($dotGit)) {
            return null;
        }

        $head = file_get_contents($dotGit.'HEAD');
        if ($head === false) {
            return null;
        }
        $head = trim(substr($head, 4));

        $hash = file_get_contents($dotGit.$head);
        if ($hash === false) {
            return null;
        }

        return trim($hash);
    }

    /**
     * @throws GitException
     */
    public function fetch(Repository $repository, bool $force = false): void
    {
        $this->ensureCloned($repository);

        if (! $force && $repository->fetched_at !== null && $repository->fetched_at->addMinutes(5)->greaterThan(now())) {
            return;
        }

        $gitRepository = $this->getGitRepository($repository);

        $gitRepository->fetch();

        $repository->markFetched();
    }

    /**
     * @throws GitException
     */
    public function ensureCloned(Repository $repository): void
    {
        $path = $this->disk->path($this->getRelativePathInDisk($repository));

        if (is_dir($path)) {
            try {
                $gitRepository = $this->git->open($path);

                if (trim($gitRepository->run('rev-parse', '--is-bare-repository')->getOutputAsString()) === 'true') {
                    return;
                }
            } catch (GitException $e) {
                // Invalid/incomplete.
            }
        }

        $this->git->cloneRepository($this->getGitHubCloneURL($repository), $path, ['--bare']);

        $repository->markFetched();
    }

    private function getRelativePathInDisk(Repository $repository): string
    {
        return (string) $repository->github_id;
    }

    private function getGitHubCloneURL(Repository $repository): string
    {
        return 'https://github.com/'.$repository->name.'.git';
    }

    private function getGitRepository(Repository $repository): GitRepository
    {
        return $this->git->open($this->disk->path($this->getRelativePathInDisk($repository)));
    }

    public function hasCommit(Repository $repository, string $sha): bool
    {
        $gitRepository = $this->getGitRepository($repository);

        try {
            $gitRepository->getCommit($sha);

            return true;
        } catch (GitException $e) {
            return false;
        }
    }

    public function archive(Repository $repository, string $sha): void
    {
        // TODO: Implement archive() method.
    }

    public function discoverTags(Repository $repository): Collection
    {
        $gitRepository = $this->getGitRepository($repository);

        $tags = $gitRepository->getTags();

        return collect($tags)->map(function (string $name) use ($gitRepository): GitTag {
            $sha = trim($gitRepository->run('rev-list', '-n', '1', $name)->getOutputAsString());

            return GitTag::from(compact('sha', 'name'));
        });
    }
}
