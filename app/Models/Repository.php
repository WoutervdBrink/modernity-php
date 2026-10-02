<?php

namespace App\Models;

use App\Data\Repository\GitHubRepository;
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

    /**
     * @return HasMany<Snapshot, $this>
     */
    public function snapshots(): HasMany
    {
        return $this->hasMany(Snapshot::class);
    }

    /**
     * @return HasMany<SearchResult, $this>
     */
    public function searchResults(): HasMany
    {
        return $this->hasMany(SearchResult::class);
    }

    #[Scope]
    public function accepted(Builder $query): Builder
    {
        return $query->whereHas('searchResults', fn (Builder $query) => $query->whereNull('rejection_reason'));
    }

    public function markSnapshotsDiscovered(): void
    {
        if ($this->snapshots_discovered_at !== null) {
            throw new LogicException('Snapshots of repository were already marked discovered');
        }

        $this->snapshots_discovered_at = now();
        $this->save();
    }

    protected function casts(): array
    {
        return [
            'github_id' => 'integer',
            'snapshots_discovered_at' => 'timestamp',
        ];
    }

    protected function snapshotsDiscovered(): Attribute
    {
        return Attribute::get(fn (): bool => $this->snapshots_discovered_at !== null);
    }
}
