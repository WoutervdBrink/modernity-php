<?php

namespace App\Services\Analysis\Php;

use App\Parser\Visitors\NodeFeatureConstraintDetectingVisitor;
use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;

final readonly class FeatureConstraintAnnotator
{
    private NodeTraverser $traverser;

    public function __construct()
    {
        $this->traverser = new NodeTraverser;
        $this->traverser->addVisitor(new NodeFeatureConstraintDetectingVisitor);
    }

    /**
     * @param  array<Stmt>  $ast
     * @return array<Stmt>
     */
    public function annotate(array $ast): array
    {
        return $this->traverser->traverse($ast);
    }
}
