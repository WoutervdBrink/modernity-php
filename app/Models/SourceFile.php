<?php

namespace App\Models;

use App\Data\Analysis\RepositoryFile;
use App\Data\Analysis\Turbo\TurboDocument;
use App\Models\Enums\ContentEncoding;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Throwable;

#[Table(key: 'sha256', keyType: 'string', incrementing: false)]
final class SourceFile extends Model
{
    protected $casts = [
        'size' => 'integer',
        'analysis' => TurboDocument::class,
        'content_encoding' => ContentEncoding::class,
    ];

    /**
     * @param  callable(): TurboDocument  $analyze
     */
    public static function record(RepositoryFile $file, callable $analyze): self
    {
        $shouldEncode = self::shouldContentsBeEncodedWithBase64($file->contents);

        self::query()->insertOrIgnore([
            'sha256' => $file->sha256,
            'size' => $file->size,
            'contents' => $shouldEncode ? base64_encode($file->contents) : $file->contents,
            'content_encoding' => $shouldEncode ? 'base64' : 'plain',
            'analysis' => null,
            'created_at' => now()->toImmutable(),
        ]);

        /** @var SourceFile $sourceFile */
        $sourceFile = self::query()
            ->lockForUpdate()
            ->findOrFail($file->sha256);

        if ($sourceFile->analysis === null) {
            try {
                $sourceFile->analysis = $analyze();
                $sourceFile->save();
            } catch (Throwable $e) {
                $sourceFile->analysis = null;
                $sourceFile->exception = $e->__toString();
                $sourceFile->save();
            }
        }

        return $sourceFile;
    }

    private static function shouldContentsBeEncodedWithBase64(string $contents): bool
    {
        return ! mb_check_encoding($contents, 'UTF-8') || str_contains($contents, "\0");
    }

    /**
     * @return BelongsTo<SourceFile, $this>
     */
    public function snapshotFiles(): BelongsTo
    {
        return $this->belongsTo(SnapshotFile::class, 'sha256', 'sha256');
    }

    /**
     * @return Attribute<bool, never>
     */
    protected function isAnalyzed(): Attribute
    {
        return Attribute::make(get: function (mixed $value, array $attributes): bool {
            return $attributes['analysis'] !== null;
        });
    }

    /**
     * @return Attribute<bool, never>
     */
    protected function hasThrownException(): Attribute
    {
        return Attribute::make(get: function (mixed $value, array $attributes): bool {
            return $attributes['exception'] !== null;
        });
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    #[Scope]
    protected function analyzed(Builder $query, bool $analyzed): Builder
    {
        return $analyzed ? $query->whereNotNull('analysis') : $query->whereNull('analysis');
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    #[Scope]
    protected function exceptionThrown(Builder $query, bool $thrown): Builder
    {
        return $thrown ? $query->whereNotNull('exception') : $query->whereNull('exception');
    }
}
