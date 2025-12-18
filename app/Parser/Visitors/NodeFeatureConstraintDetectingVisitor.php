<?php

namespace App\Parser\Visitors;

use App\Catalogue\Feature;
use Override;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

/**
 * This visitor sets the 'constraint' attribute on every node, indicating the minimum/maximum PHP version that would
 * support the composition/nature of the node.
 *
 * The per-node constraint can be retrieved via the 'constraint' attribute, e.g. via {@link Node::getAttribute()}.
 *
 * @see ASTFeatureConstraintMergingVisitor to determine the constraint for an entire parse tree.
 */
final class NodeFeatureConstraintDetectingVisitor extends NodeVisitorAbstract
{
    #[Override]
    public function enterNode(Node $node): null
    {
        $constraint = Feature::constraintFor($node);

        $node->setAttribute('constraint', $constraint);

        return null;
    }
}
