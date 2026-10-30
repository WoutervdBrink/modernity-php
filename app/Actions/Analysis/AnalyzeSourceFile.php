<?php

namespace App\Actions\Analysis;

use App\Contracts\Analysis\ModernityAnalyzer;
use App\Data\Analysis\RepositoryFile;
use App\Data\Analysis\Turbo\TurboDocument;
use App\Models\SourceFile;
use Throwable;

final class AnalyzeSourceFile
{
    public function __construct(
        private ModernityAnalyzer $analyzer,
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(RepositoryFile $file): SourceFile
    {
        return SourceFile::record($file, fn (): TurboDocument => $this->analyzer->analyze($file->contents));
    }
}
