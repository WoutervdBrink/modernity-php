<?php

namespace App\Models;

use App\Data\Git\GitTag;
use App\Data\GitHub\GitHubRepository;
use App\Models\Enums\RepositorySnapshotDiscoveryStatus;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LogicException;

final class Repository extends Model
{
    protected $fillable = [
        'github_id',
        'name',
        'description',
    ];

    public static function fromGitHubRepository(GitHubRepository $repository): Repository
    {
        return self::firstOrCreate(
            ['github_id' => $repository->id],
            [
                'name' => $repository->full_name,
                'description' => $repository->description,
            ]
        );
    }

    #[Scope]
    public function accepted(Builder $query, bool $accepted): Builder
    {
        if ($accepted) {
            return $query->whereHas('searchResults', fn (Builder $query) => $query->whereNull('rejection_reason'));
        } else {
            return $query->whereHas('searchResults', fn (Builder $query) => $query->whereNotNull('rejection_reason'));
        }
    }

    #[Scope]
    public function snapshotsDiscovered(Builder $query, bool $discovered): Builder
    {
        if ($discovered) {
            return $query->whereNotNull('snapshots_discovered_at');
        } else {
            return $query->whereNull('snapshots_discovered_at');
        }
    }

    #[Scope]
    public function fetched(Builder $query, bool $fetched): Builder
    {
        return $fetched ? $query->whereNotNull('fetched_at') : $query->whereNull('fetched_at');
    }

    public function markSnapshotsDiscovered(): void
    {
        $this->setSnapshotDiscoveryStatus(RepositorySnapshotDiscoveryStatus::COMPLETED);
        $this->snapshots_discovered_at = now();
        $this->save();
    }

    private function setSnapshotDiscoveryStatus(RepositorySnapshotDiscoveryStatus $to): void
    {
        if (! $this->snapshot_discovery_status->isTransitionAllowed($to)) {
            throw new LogicException('Transitioning from status '.$this->snapshot_discovery_status->name.' to '.$to->name.' is not allowed.');
        }

        $this->snapshot_discovery_status = $to;
    }

    #[Scope]
    public function withAccepted(Builder $query): Builder
    {
        return $query->withExists([
            'searchResults as is_accepted' => fn (Builder $query) => $query->whereNull('rejection_reason'),
        ]);
    }

    public function markFetched(): void
    {
        $this->fetched_at = now();
        $this->save();
    }

    public function recordSnapshot(GitTag $tag): Snapshot
    {
        $existing = $this->snapshots()
            ->where('tag', $tag->name)
            ->first();

        if ($existing !== null) {
            return $existing;
        }

        $snapshot = $this->snapshots()->make();

        $snapshot->tag = $tag->name;
        $snapshot->commit_sha = $tag->sha;
        $snapshot->semver = $tag->semver;

        $snapshot->save();

        return $snapshot;
    }

    /**
     * @return HasMany<Snapshot, $this>
     */
    public function snapshots(): HasMany
    {
        return $this->hasMany(Snapshot::class);
    }

    public function queueSnapshotDiscovery(): void
    {
        $this->setSnapshotDiscoveryStatus(RepositorySnapshotDiscoveryStatus::QUEUED);
        $this->save();
    }

    public function startSnapshotDiscovery(): void
    {
        $this->setSnapshotDiscoveryStatus(RepositorySnapshotDiscoveryStatus::RUNNING);
        $this->save();
    }

    public function failSnapshotDiscovery(): void
    {
        $this->setSnapshotDiscoveryStatus(RepositorySnapshotDiscoveryStatus::FAILED);
        $this->save();
    }

    protected function isDiscoveringSnapshots(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->snapshot_discovery_status->isActive();
            }
        );
    }

    protected function isAccepted(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes): bool {
                // If withExists() already supplied the value, use it.
                if (array_key_exists('is_accepted', $attributes)) {
                    return (bool) $attributes['is_accepted'];
                }

                // Otherwise, e.g. Repository::find($id), query it lazily.
                return $this->searchResults()
                    ->whereNull('rejection_reason')
                    ->exists();
            },
        );
    }

    /**
     * @return HasMany<SearchResult, $this>
     */
    public function searchResults(): HasMany
    {
        return $this->hasMany(SearchResult::class);
    }

    protected function isFetched(): Attribute
    {
        return Attribute::make(get: function (mixed $value, array $attributes): bool {
            return $attributes['fetched_at'] !== null;
        });
    }

    protected function casts(): array
    {
        return [
            'github_id' => 'integer',
            'snapshots_discovered_at' => 'immutable_datetime',
            'fetched_at' => 'immutable_datetime',
            'snapshot_discovery_status' => RepositorySnapshotDiscoveryStatus::class,
        ];
    }
}
