<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Snapshot extends Model
{
    protected function casts(): array
    {
        return [
            'committed_at' => 'timestamp',
            'downloaded_at' => 'timestamp',
            'indexed_at' => 'timestamp',
        ];
    }

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
}
