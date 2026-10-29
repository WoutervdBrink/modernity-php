<?php

namespace App\Services\Analysis\Php;

use App\Parser\Visitors\NodeFeatureConstraintDetectingVisitor;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;

final readonly class FeatureConstraintAnnotator
{
    private NodeTraverser $traverser;

    public function __construct()
    {
        $this->traverser = new NodeTraverser;
        $this->traverser->addVisitor(new NameResolver);
        $this->traverser->addVisitor(new NodeFeatureConstraintDetectingVisitor);
    }

    /**
     * @param  array<Node>  $ast
     * @return array<Node>
     */
    public function annotate(array $ast): array
    {
        return $this->traverser->traverse($ast);
    }
}
