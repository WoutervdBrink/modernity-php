<?php

namespace App\Models;

use App\Data\Analysis\RepositoryFile;
use App\Data\Analysis\Turbo\TurboDocument;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Throwable;

#[Table(key: 'sha256', keyType: 'string', incrementing: false)]
final class SourceFile extends Model
{
    protected $casts = [
        'size' => 'integer',
        'analysis' => TurboDocument::class,
    ];

    /**
     * @param  callable(): TurboDocument  $analyze
     */
    public static function record(RepositoryFile $file, callable $analyze): self
    {
        self::query()->insertOrIgnore([
            'sha256' => $file->sha256,
            'size' => $file->size,
            'contents' => $file->contents,
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
}
