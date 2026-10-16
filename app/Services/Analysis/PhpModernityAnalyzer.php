<?php

namespace App\Services\Analysis;

use App\Contracts\Analysis\ModernityAnalyzer;
use App\Data\Analysis\Turbo\TurboDocument;
use App\Services\Analysis\Php\DeterministicPhpParser;
use App\Services\Analysis\Php\FeatureConstraintAnnotator;
use App\Services\Analysis\Php\TurboDocumentWriter;

final class PhpModernityAnalyzer implements ModernityAnalyzer
{
    public function __construct(
        private DeterministicPhpParser $parser,
        private FeatureConstraintAnnotator $annotator,
        private TurboDocumentWriter $writer,
    ) {}

    public function analyze(string $code): TurboDocument
    {
        $ast = $this->parser->parse($code);

        $ast = $this->annotator->annotate($ast);

        return $this->writer->write($ast);
    }
}
