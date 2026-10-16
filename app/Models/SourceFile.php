<?php

namespace App\Models;

use App\Data\Analysis\RepositoryFile;
use App\Data\Analysis\Turbo\TurboDocument;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table(key: 'sha256', keyType: 'string', incrementing: false)]
final class SourceFile extends Model
{
    public static function record(RepositoryFile $fileContents, TurboDocument $analysis): self
    {
        $sourceFile = new self;

        $sourceFile->sha256 = $fileContents->sha256;
        $sourceFile->size = $fileContents->size;
        $sourceFile->contents = $fileContents->contents;

        $sourceFile->analysis = $analysis;

        $sourceFile->save();

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

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'analysis' => TurboDocument::class,
        ];
    }
}
