<?php

namespace App\Catalogue;

use App\Language\PhpVersionConstraint;
use PhpParser\Node;

final class RecursiveConstraintInspector
{
    /**
     * @param  Node|Node[]  $value
     * @param  callable(Node): ?PhpVersionConstraint  $callback
     */
    public static function inspect(
        mixed $value,
        callable $callback,
    ): PhpVersionConstraint {
        if ($value instanceof Node) {
            $constraint = $callback($value) ?? PhpVersionConstraint::open();

            foreach ($value->getSubNodeNames() as $name) {
                $constraint = $constraint->merge(
                    self::inspect($value->{$name}, $callback),
                );
            }

            return $constraint;
        }

        if (is_array($value)) {
            $constraint = PhpVersionConstraint::open();

            foreach ($value as $item) {
                $constraint = $constraint->merge(
                    self::inspect($item, $callback),
                );
            }

            return $constraint;
        }

        return PhpVersionConstraint::open();
    }
}
