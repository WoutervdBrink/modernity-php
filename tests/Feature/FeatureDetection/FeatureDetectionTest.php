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

        $stmts = $parser->parse($example->code);
        $traverser->traverse($stmts);

        $constraint = $minMax->constraint;

        expect($constraint->min)->toBe($example->minVersion)
            ->and($constraint->max)->toBe($example->maxVersion);
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
