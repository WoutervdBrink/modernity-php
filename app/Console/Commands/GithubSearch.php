<?php

namespace App\Console\Commands;

use App\GitHub\Data\GitHubRepository;
use Carbon\CarbonImmutable;
use GrahamCampbell\GitHub\GitHubManager;
use Illuminate\Support\Collection;
use LaravelZero\Framework\Commands\Command;

final class GithubSearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'github:search
                            {--cutoff= : Cutoff date. Repositories created after this date will not be considered.}
                            {--php= : Minimum percentage of code that should be written in PHP.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search for repositories on GitHub.';

    private function buildSearchQuery(): string
    {
        $query = 'language:php';

        if (($cutoff = $this->option('cutoff')) !== null && is_string($cutoff)) {
            $cutoffDate = CarbonImmutable::parse($cutoff)->format('Y-m-d');
            $query .= ' created:<='.$cutoffDate;
        }

        return $query;
    }

    /**
     * @return Collection<int, GitHubRepository>
     */
    private function getRepositoriesViaSearchQuery(GitHubManager $gh): Collection
    {
        $response = $gh->search()->repositories($this->buildSearchQuery(), 'stars');

        return GitHubRepository::collect(collect($response['items']));
    }

    /**
     * Execute the console command.
     */
    public function handle(GitHubManager $gh): void
    {
        $repositories = $this->getRepositoriesViaSearchQuery($gh);

        if (($php = $this->option('php')) !== null && is_numeric($php)) {
            foreach ($repos as $repo) {
                $owner = explode('/', $repo->full_name, 2);

                $languageInfo = $gh->repos()->languages($owner[0], $owner[1]);

                $phpShare = (($languageInfo['PHP'] ?? 0) / array_sum($languageInfo)) * 100;

                if ($phpShare < $php) {
                    $this->warn('Skipping repository '.$repo->full_name.' as its PHP share ('.$phpShare.') is below the minimum ('.$php.')');
                }
            }
        }
    }
}
