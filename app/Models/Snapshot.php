<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LogicException;

final class Snapshot extends Model
{
    /**
     * @return BelongsTo<Repository, $this>
     */
    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }

    public function recordFile(string $path, SourceFile $sourceFile): void
    {
        $existing = $this->files()
            ->where('path', $path)
            ->first();

        if ($existing !== null) {
            if ($existing->sha256 !== $sourceFile->sha256) {
                throw new LogicException(
                    sprintf(
                        'Snapshot file "%s" was already recorded with SHA %s, but is now %s.',
                        $path,
                        $existing->sha256,
                        $sourceFile->sha256,
                    ),
                );
            }

            return;
        }

        $snapshotFile = $this->files()->make();

        $snapshotFile->path = $path;
        $snapshotFile->sha256 = $sourceFile->sha256;

        $snapshotFile->save();
    }

    /**
     * @return HasMany<SnapshotFile, $this>
     */
    public function files(): HasMany
    {
        return $this->hasMany(SnapshotFile::class);
    }

    public function markAnalyzed(): void
    {
        $this->indexed_at = now();
        $this->save();
    }

    /**
     * @param  Builder<Snapshot>  $query
     * @return Builder<Snapshot>
     */
    #[Scope]
    protected function semverDetected(Builder $query, bool $detected): Builder
    {
        return $detected ? $query->whereNotNull('semver') : $query->whereNull('semver');
    }

    /**
     * @param  Builder<Snapshot>  $query
     * @return Builder<Snapshot>
     */
    #[Scope]
    protected function downloaded(Builder $query, bool $downloaded): Builder
    {
        if ($downloaded) {
            return $query->whereNotNull('downloaded_at');
        } else {
            return $query->whereNull('downloaded_at');
        }
    }

    protected $casts = [
        'committed_at' => 'datetime',
        'downloaded_at' => 'datetime',
        'indexed_at' => 'datetime',
    ];
}
