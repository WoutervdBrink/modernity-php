<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Repository extends Model
{
    protected function casts(): array
    {
        return [
            'github_id' => 'integer',
            'snapshots_discovered_at' => 'timestamp',
        ];
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

    protected function snapshotsDiscovered(): Attribute
    {
        return Attribute::get(fn (): bool => $this->snapshots_discovered_at !== null);
    }
}
