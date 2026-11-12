<?php

namespace App\Services\Modernity\Php;

use App\Contracts\Modernity\ModernityAnalyzer;
use App\Data\Analysis\Turbo\TurboDocument;

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
