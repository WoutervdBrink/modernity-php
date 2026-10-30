<?php

namespace App\Actions\Analysis;

use App\Contracts\Analysis\ModernityAnalyzer;
use App\Data\Analysis\RepositoryFile;
use App\Models\SourceFile;
use Illuminate\Support\Facades\DB;
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
        return DB::transaction(function () use ($file): SourceFile {
            $existing = SourceFile::find($file->sha256);

            if ($existing !== null) {
                if (! $existing->hasAnalysis()) {
                    $existing->recordAnalysis($this->analyzer->analyze($file->contents));
                }

                return $existing;
            }

            $analysis = $this->analyzer->analyze($file->contents);

            return SourceFile::record($file, $analysis);
        });
    }
}
