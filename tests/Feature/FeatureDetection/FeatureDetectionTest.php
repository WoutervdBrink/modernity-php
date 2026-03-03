<?php

namespace Tests\Feature\FeatureDetection;

use App\Parser\CachingParserFactory;
use App\Parser\Visitors\ASTFeatureConstraintMergingVisitor;
use App\Parser\Visitors\NodeFeatureConstraintDetectingVisitor;
use DirectoryIterator;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use Tests\Fixtures\ExampleRepository;

it('detects features correctly', function () {
    /** @var ExampleRepository[] $repositories */
    $repositories = [];

    $iterator = new DirectoryIterator(__DIR__.'/../../Fixtures/FeatureDetection');

    foreach ($iterator as $file) {
        if (! $file->isDot() && $file->isDir()) {
            $repositoriesInDirectory = ExampleRepository::fromDirectory($file->getFilename(), $file->getRealPath());

            foreach ($repositoriesInDirectory as $repository) {
                $repositories[] = $repository;
            }
        }
    }

    $parserFactory = new CachingParserFactory;

    foreach ($repositories as $repository) {
        foreach ($repository->examples() as $example) {
            /** @var Parser $parser */
            $parser = $parserFactory->createForVersion($example->parserVersion);

            $traverser = new NodeTraverser;

            $traverser->addVisitor(new NodeFeatureConstraintDetectingVisitor);
            $traverser->addVisitor($minMax = new ASTFeatureConstraintMergingVisitor);

            $stmts = $parser->parse($example->code);
            $traverser->traverse($stmts);

            $constraint = $minMax->constraint;

            $identifier = sprintf('[%s] %s', $repository->name, $example->description);

            if ($example->minVersion !== false) {
                expect($constraint->min)
                    ->toBe(
                        $example->minVersion,
                        sprintf(
                            '(%s) Expected minimum version %s',
                            $identifier,
                            $example->minVersion?->toVersionString() ?? 'none'
                        ),
                    );
            }

            if ($example->maxVersion !== false) {
                expect($constraint->max)
                    ->toBe(
                        $example->maxVersion,
                        sprintf(
                            '(%s) Expected maximum version %s',
                            $identifier,
                            $example->maxVersion?->toVersionString() ?? 'none'
                        )
                    );
            }

        }
    }
});
