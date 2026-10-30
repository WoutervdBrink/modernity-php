<?php

namespace App\Models;

use App\Data\Analysis\RepositoryFile;
use App\Data\Analysis\Turbo\TurboDocument;
use App\Services\Analysis\Php\SourceCannotBeParsedException;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

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
        ]);

        $sourceFile = self::query()
            ->lockForUpdate()
            ->findOrFail($file->sha256);

        if ($sourceFile->analysis === null) {
            try {
                $sourceFile->analysis = $analyze();
                $sourceFile->save();
            } catch (SourceCannotBeParsedException) {
                //
            }
        }

        return $sourceFile;
    }

    public function hasAnalysis(): bool
    {
        return $this->analysis !== null;
    }

    public function recordAnalysis(TurboDocument $analysis): void
    {
        $this->analysis = $analysis;
        $this->save();
    }
}
