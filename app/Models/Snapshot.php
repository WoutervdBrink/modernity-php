<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Snapshot extends Model
{
    /**
     * @return BelongsTo<Repository, $this>
     */
    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }

    /**
     * @return HasMany<SnapshotFile, $this>
     */
    public function files(): HasMany
    {
        return $this->hasMany(SnapshotFile::class);
    }

    #[Scope]
    public function downloaded(Builder $query, bool $downloaded): Builder
    {
        if ($downloaded) {
            return $query->whereNotNull('downloaded_at');
        } else {
            return $query->whereNull('downloaded_at');
        }
    }

    protected function casts(): array
    {
        return [
            'committed_at' => 'datetime',
            'downloaded_at' => 'datetime',
            'indexed_at' => 'datetime',
        ];
    }
}
