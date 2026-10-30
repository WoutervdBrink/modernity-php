<?php

namespace App\Services\Analysis\Php\Catalogue;

use App\Services\Analysis\Php\Language\PhpVersionConstraint;
use PhpParser\Node;

final class RecursiveConstraintInspector
{
    /**
     * @param  Node|Node[]|mixed  $value
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

        $constraint = PhpVersionConstraint::open();

        if (is_array($value)) {
            foreach ($value as $item) {
                $constraint = $constraint->merge(
                    self::inspect($item, $callback),
                );
            }
        }

        return $constraint;
    }
}
