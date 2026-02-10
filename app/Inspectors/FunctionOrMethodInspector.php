<?php

namespace App\Inspectors;

use App\Language\PhpVersion;
use App\Language\PhpVersionConstraint;
use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\Error;

final class FunctionOrMethodInspector implements Inspector
{
    /**
     * @template T of Node\Stmt\Function_|Node\Stmt\ClassMethod|Node\Expr\Closure|Node\Expr\ArrowFunction
     *
     * @param  T  $node
     */
    #[Override]
    public static function inspect(Node $node): PhpVersionConstraint
    {
        /** @var ?PhpVersion $since */
        $since = null;

        if (! empty($node->returnType)) {
            $returnType = ($node->returnType instanceof Node\Identifier || $node->returnType instanceof Node\Name)
                ? $node->returnType->toString()
                : '';

            // As of PHP 8.1, the noreturn type can be specified to indicate a method or function never returns.
            // https://wiki.php.net/rfc/noreturn_type
            if ($returnType === 'noreturn') {
                $since = PhpVersion::PHP_8_1;
            }

            // As of PHP 7.2, the 'object' type was introduced as a return type.
            // https://wiki.php.net/rfc/object-typehint
            if (empty($since) && $returnType === 'object') {
                $since = PhpVersion::PHP_7_2;
            }

            // In any case, having a return type at all was introduced in PHP 7.0.
            $since = $since ?? PhpVersion::PHP_7_0;
        }

        /** @var ?PhpVersion $until */
        $until = null;

        /** @var array<string, bool> $paramMap */
        $paramMap = [];

        foreach ($node->params as $param) {
            // Scalar type declarations were introduced in PHP 7.0.
            if ($param->type instanceof Node\Identifier) {
                if (
                    $param->type->name === 'string' ||
                    $param->type->name === 'int' ||
                    $param->type->name === 'float' ||
                    $param->type->name === 'bool'
                ) {
                    $since = PhpVersion::max($since, PhpVersion::PHP_7_0);
                }
            }

            if ($param->var instanceof Error) {
                continue;
            }

            $paramName = $param->var->name;
            if ($paramName instanceof Node\Expr) {
                continue;
            }

            // As of PHP 7.0, it is no longer possible to define two or more function parameters with the same name.
            if ($paramMap[$paramName] ?? null) {
                $until = PhpVersion::PHP_5_6;
                break;
            }

            $paramMap[$paramName] = true;
        }

        return PhpVersionConstraint::between($since, $until);
    }
}
