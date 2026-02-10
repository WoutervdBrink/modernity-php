<?php

namespace Tests\Feature\FeatureDetection;

use App\Parser\CachingParserFactory;
use App\Parser\Visitors\ASTFeatureConstraintMergingVisitor;
use App\Parser\Visitors\NodeFeatureConstraintDetectingVisitor;
use DirectoryIterator;
use Generator;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use Tests\Fixtures\Example;
use Tests\Fixtures\ExampleRepository;

beforeEach(function () {
    /** @var ParserFactory parserFactory */
    $this->parserFactory = new CachingParserFactory;
});

describe('Feature detection', function () {
    it('tests example :dataset', function (Example $example) {
        $traverser = new NodeTraverser;
        /** @var Parser $parser */
        $parser = $this->parserFactory->createForVersion($example->parserVersion);

        $traverser->addVisitor(new NodeFeatureConstraintDetectingVisitor);
        $traverser->addVisitor($minMax = new ASTFeatureConstraintMergingVisitor);

        if ($example->everyLine) {
            // The example has EveryLine: true, meaning **every** line in the code has to conform to the expectations.
            $codes = explode("\n", $example->code);
            // Shift '<?php' from the start of the code.
            $start = array_shift($codes);
            // Pop '? >' from the end of the code.
            array_pop($codes);
            // Zip the starting line to every line in the example.
            $codes = array_map(fn (string $code): string => $start."\n".$code, $codes);
        } else {
            $codes = [$example->code];
        }

        foreach ($codes as $i => $code) {
            $stmts = $parser->parse($code);
            $traverser->traverse($stmts);

            $constraint = $minMax->constraint;

            expect($constraint->min)->toBe($example->minVersion, '(Code '.$i.') Expected minimum version '.($example->minVersion?->toVersionString() ?? 'none'))
                ->and($constraint->max)->toBe($example->maxVersion, '(Code '.$i.') Expected maximum version '.($example->maxVersion?->toVersionString() ?? 'none'));
        }
    })->with(/** @return Generator<Example> */ function (): Generator {
        $iterator = new DirectoryIterator(__DIR__.'/../../Fixtures/FeatureDetection');

        foreach ($iterator as $file) {
            if (! $file->isDot() && $file->isDir()) {
                $repositories = ExampleRepository::fromDirectory($file->getRealPath());

                foreach ($repositories as $repository) {
                    yield from $repository->examples();
                }
            }
        }
    });
});
