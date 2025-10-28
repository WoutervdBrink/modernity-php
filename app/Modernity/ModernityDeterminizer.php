<?php

namespace App\Modernity;

use App\Language\PhpVersion;
use App\Language\PhpVersionConstraint;
use App\Language\PhpVersionVector;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use RuntimeException;

class ModernityDeterminizer
{
    /**
     * @param  Stmt[]  $stmts
     */
    public function determine(array $stmts): PhpVersionVector
    {
        return $this->visitArray($stmts);
    }

    /**
     * @param  array<PhpVersionVector|null>  $vectors
     */
    private function mergeVectors(array $vectors): PhpVersionVector
    {
        $vectors = array_filter($vectors);

        $vectors = array_map(fn (PhpVersionVector $vector): PhpVersionVector => $vector->rescale(), $vectors);

        return array_reduce($vectors, fn (PhpVersionVector $carry, PhpVersionVector $item): PhpVersionVector => $carry->add($item), PhpVersionVector::zero())->rescale();
    }

    private function constraintToVector(PhpVersionConstraint $constraint): ?PhpVersionVector
    {
        if ($constraint->min === null && $constraint->max === null) {
            return null;
        }

        $vector = PhpVersionVector::zero();

        $min = $constraint->min ?? PhpVersion::getOldestSupported();
        $max = $constraint->max ?? PhpVersion::getNewestSupported();

        for ($cursor = $min; $cursor !== null && $cursor->isOlderThan($max); $cursor = $cursor->next()) {
            $vector[$cursor] = 1.0;
        }

        return $vector;
    }

    private function visitNode(Node $node): PhpVersionVector
    {
        $constraint = $node->getAttribute('constraint');

        if (! $constraint instanceof PhpVersionConstraint) {
            throw new RuntimeException('Missing required attribute "constraint"');
        }

        /** @var PhpVersionVector[] $vectors */
        $vectors = [$this->constraintToVector($constraint)];

        foreach ($node->getSubNodeNames() as $subNodeName) {
            $child = $node->{$subNodeName};

            if (is_array($child)) {
                $vectors[] = $this->visitArray($child);

                continue;
            }

            if ($child instanceof Node) {
                $vectors[] = $this->visitNode($child);

                continue;
            }

            if (is_scalar($child) || is_null($child)) {
                continue;
            }

            throw new RuntimeException('Unknown child node type '.$node->getType().'->'.$subNodeName);
        }

        return $this->mergeVectors($vectors);
    }

    /**
     * @param  Node[]  $nodes
     */
    private function visitArray(array $nodes): PhpVersionVector
    {
        return $this->mergeVectors(array_map($this->visitNode(...), $nodes));
    }
}
