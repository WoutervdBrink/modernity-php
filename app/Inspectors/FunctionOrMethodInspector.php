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

        /** @var ?PhpVersion $until */
        $until = null;

        // As of PHP 8.0, __autoload() autoloading behavior is removed.
        if ($node instanceof Node\Stmt\Function_ && $node->name->name === '__autoload') {
            $until = PhpVersion::PHP_7_4;
        }

        if (! empty($node->returnType)) {
            $returnType = ($node->returnType instanceof Node\Identifier || $node->returnType instanceof Node\Name)
                ? $node->returnType->toString()
                : '';

            // As of PHP 8.1, the noreturn type can be specified to indicate a method or function never returns.
            // https://wiki.php.net/rfc/noreturn_type
            if ($returnType === 'noreturn') {
                $since = PhpVersion::PHP_8_1;
            }

            // As of PHP 8.0, the static return type can be specified for methods.
            if (($node instanceof Node\Stmt\ClassMethod) && $returnType === 'static') {
                $since = PhpVersion::PHP_8_0;
            }

            // As of PHP 8.0, the 'mixed' type was introduced as a return type.
            if ($returnType === 'mixed') {
                $since = PhpVersion::PHP_8_0;
            }

            // As of PHP 7.2, the 'object' type was introduced as a return type.
            // https://wiki.php.net/rfc/object-typehint
            if (empty($since) && $returnType === 'object') {
                $since = PhpVersion::PHP_7_2;
            }

            // As of PHP 7.1, the 'void' and 'iterable' types were introduced as return types.
            if ($returnType === 'void' || $returnType === 'iterable') {
                $since = PhpVersion::PHP_7_1;
            }

            // In any case, having a return type at all was introduced in PHP 7.0.
            $since = $since ?? PhpVersion::PHP_7_0;
        }

        /** @var array<string, bool> $paramMap */
        $paramMap = [];

        foreach ($node->params as $param) {
            if ($param->type instanceof Node\Identifier) {
                // Scalar type declarations were introduced in PHP 7.0.
                if (
                    $param->type->name === 'string' ||
                    $param->type->name === 'int' ||
                    $param->type->name === 'float' ||
                    $param->type->name === 'bool'
                ) {
                    $since = PhpVersion::max($since, PhpVersion::PHP_7_0);
                }

                // The 'iterable' type declaration was introduced in PHP 7.1.
                if ($param->type->name === 'iterable') {
                    $since = PhpVersion::max($since, PhpVersion::PHP_7_1);
                }

                // The 'object' type declaration was introduced in PHP 7.2.
                if ($param->type->name === 'object') {
                    $since = PhpVersion::max($since, PhpVersion::PHP_7_2);
                }

                // The 'mixed' type declaration was introduced in PHP 8.0.
                if ($param->type->name === 'mixed') {
                    $since = PhpVersion::max($since, PhpVersion::PHP_8_0);
                }
            }

            if ($param->var instanceof Error) {
                continue;
            }

            $paramName = $param->var->name;
            if ($paramName instanceof Node\Expr) {
                continue;
            }

            // As of PHP 7.1, it is no longer possible to use $this as a parameter.
            if ($paramName === 'this') {
                $until = PhpVersion::min($until, PhpVersion::PHP_7_0);
            }

            // As of PHP 7.0, it is no longer possible to define two or more function parameters with the same name.
            if ($paramMap[$paramName] ?? null) {
                $until = PhpVersion::min($until, PhpVersion::PHP_5_6);
                break;
            }

            $paramMap[$paramName] = true;
        }

        return PhpVersionConstraint::between($since, $until);
    }
}
