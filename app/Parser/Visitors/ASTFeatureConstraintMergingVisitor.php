<?php

namespace App\Parser\Visitors;

use App\Language\PhpVersionConstraint;
use Override;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

/**
 * This visitor calculates the minimum/maximum PHP version that would support the contents of the visited AST, based on
 * constraints determined by the {@link NodeFeatureConstraintDetectingVisitor} visitor.
 *
 * The visitor assumes the tree has (just) been analyzed by a {@link NodeFeatureConstraintDetectingVisitor} visitor, and
 * merges an initially open {@link PhpVersionConstraint} with every {@link PhpVersionConstraint} it finds in the AST.
 *
 * The final version constraint can be retrieved using the {@link ASTFeatureConstraintMergingVisitor::$constraint}
 * field.
 */
final class ASTFeatureConstraintMergingVisitor extends NodeVisitorAbstract
{
    /**
     * @var PhpVersionConstraint Current or final version constraint.
     */
    public private(set) PhpVersionConstraint $constraint {
        get {
            if (empty($this->constraint)) {
                return PhpVersionConstraint::open();
            }

            return $this->constraint;
        }

        set(PhpVersionConstraint $constraint) {
            $this->constraint = $constraint;
        }
    }

    public function __construct()
    {
        $this->constraint = PhpVersionConstraint::open();
    }

    #[Override]
    public function beforeTraverse(array $nodes): null
    {
        $this->constraint = PhpVersionConstraint::open();

        return null;
    }

    #[Override]
    public function leaveNode(Node $node): null
    {
        if ($node->hasAttribute('constraint')) {
            /** @var PhpVersionConstraint $constraint */
            $constraint = $node->getAttribute('constraint', PhpVersionConstraint::open());
            $this->constraint = $this->constraint->merge($constraint);
        }

        return null;
    }
}
