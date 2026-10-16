<?php

namespace App\Services\Analysis\Php;

use App\Language\PhpVersion as AppPhpVersion;
use PhpParser\Error;
use PhpParser\Node\Stmt;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\PhpVersion as ParserPhpVersion;

final readonly class DeterministicPhpParser
{
    /**
     * @var array<Parser>
     */
    private array $supportedParsers;

    public function __construct(ParserFactory $parserFactory)
    {
        $supportedParsers = [];

        $versions = AppPhpVersion::orderedCases();

        for ($i = count($versions) - 1; $i >= 0; $i--) {
            $version = $versions[$i];
            $supportedParsers[] = $parserFactory->createForVersion(ParserPhpVersion::fromString($version->toVersionString()));
        }

        $this->supportedParsers = $supportedParsers;
    }

    /**
     * @return array<Stmt>
     */
    public function parse(string $code): array
    {
        foreach ($this->supportedParsers as $parser) {
            try {
                $stmts = $parser->parse($code);

                if ($stmts !== null) {
                    return $stmts;
                }
            } catch (Error) {
                // Try the next one.
            }
        }

        throw new SourceCannotBeParsedException;
    }
}
