<?php

namespace App\Commands;

use App\Language\PhpVersionConstraint;
use App\Parser\Visitors\ASTFeatureConstraintMergingVisitor;
use App\Parser\Visitors\NodeFeatureConstraintDetectingVisitor;
use LaravelZero\Framework\Commands\Command;
use Override;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PhpParser\PhpVersion;
use PhpParser\PrettyPrinter;

final class Main extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:main';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Main command';

    /**
     * Execute the console command.
     */
    public function handle(ParserFactory $factory, NodeTraverser $traverser): void
    {
        $parser = $factory->createForVersion(PhpVersion::fromString('8.4'));

        $prettyPrinter = new PrettyPrinter\Standard;

        $traverser->addVisitor(new NodeFeatureConstraintDetectingVisitor);
        $traverser->addVisitor($minMax = new ASTFeatureConstraintMergingVisitor);

        $traverser->addVisitor($nodeVisitor = new class extends NodeVisitorAbstract
        {
            private int $depth = -1;

            /** @var array<string|int>[] */
            public array $nodes = [];

            public function __construct() {}

            #[Override]
            public function beforeTraverse(array $nodes): null
            {
                $this->depth = -1;

                return null;
            }

            #[Override]
            public function leaveNode(Node $node): null
            {
                if ($node instanceof Node\UseItem) {
                    echo 'Use item: '.$node->type.PHP_EOL;
                }
                $this->depth--;

                return null;
            }

            #[Override]
            public function enterNode(Node $node): null
            {
                $this->depth++;

                /** @var PhpVersionConstraint $constraint */
                $constraint = $node->getAttribute('constraint') ?? PhpVersionConstraint::open();

                $min = $constraint->min?->toVersionString() ?? 'any';
                $max = $constraint->max?->toVersionString() ?? 'any';

                $this->nodes[] = [$this->depth, $node->getType(), $min, $max];

                return null;
            }
        });

        $code = <<<'CODE'
<?php
use const Foo\BAR;
CODE;
        try {
            $stmts = $parser->parse($code) ?? [];
            $stmts = $traverser->traverse($stmts);
            $this->table(['Depth', 'Node', 'Min', 'Max'], $nodeVisitor->nodes);

            $this->line($prettyPrinter->prettyPrint($stmts));

            $constraint = $minMax->constraint;

            $min = $constraint->min?->toVersionString() ?? 'any';
            $max = $constraint->max?->toVersionString() ?? 'any';

            $this->info('Minimum version: '.$min);
            $this->info('Maximum version: '.$max);

        } catch (Error $e) {
            $this->error($e->getMessage());
        }
    }
}
