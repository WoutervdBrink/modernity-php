<?php

namespace App\Models;

use App\Data\Repository\GitHubRepository;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * @return HasMany<Snapshot, $this>
     */
    public function snapshots(): HasMany
    {
        return $this->hasMany(Snapshot::class);
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

    public function markSnapshotsDiscovered(): void
    {
        $this->snapshots_discovered_at = now();
        $this->save();
    }

    #[Scope]
    public function withAccepted(Builder $query): Builder
    {
        return $query->withExists([
            'searchResults as is_accepted' => fn (Builder $query) => $query->whereNull('rejection_reason'),
        ]);
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

    protected function casts(): array
    {
        return [
            'github_id' => 'integer',
            'snapshots_discovered_at' => 'timestamp',
        ];
    }
}
