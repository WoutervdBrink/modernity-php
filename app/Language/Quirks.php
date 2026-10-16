<?php

namespace App\Language;

use PhpParser\Node\Expr;
use PhpParser\Node\Scalar;

final class Quirks
{
    /**
     * Built-in variables that are always available in all scopes.
     *
     * Some earlier PHP versions allowed using these variable names in places that made no sense, e.g. as function
     * arguments.
     *
     * @link https://www.php.net/manual/en/language.variables.superglobals.php
     */
    private const array SUPERGLOBALS = [
        'GLOBALS',
        '_COOKIE',
        '_ENV',
        '_FILES',
        '_GET',
        '_POST',
        '_REQUEST',
        '_SERVER',
        '_SESSION',
    ];

    /**
     * Globally reserved keywords that became semi-reserved in PHP 7.0.
     *
     * Before PHP 7.0, it was not allowed to give a method or function the same name as one of these keywords.
     *
     * @link https://wiki.php.net/rfc/context_sensitive_lexer
     */
    private const array SEMI_RESERVED_KEYWORDS = [
        'abstract',
        'and',
        'array',
        'as',
        'break',
        'callable',
        'case',
        'catch',
        'class',
        'clone',
        'const',
        'continue',
        'declare',
        'default',
        'die',
        'do',
        'echo',
        'else',
        'elseif',
        'enddeclare',
        'endfor',
        'endforeach',
        'endif',
        'endswitch',
        'endwhile',
        'exit',
        'extends',
        'final',
        'finally',
        'for',
        'foreach',
        'function',
        'global',
        'goto',
        'if',
        'implements',
        'include',
        'include_once',
        'instanceof',
        'insteadof',
        'interface',
        'list',
        'namespace',
        'new',
        'or',
        'parent',
        'print',
        'private',
        'protected',
        'public',
        'require',
        'require_once',
        'return',
        'self',
        'static',
        'switch',
        'throw',
        'trait',
        'try',
        'use',
        'var',
        'while',
        'xor',
        'yield',
    ];

    public static function isSemiReservedKeyword(string $keyword): bool
    {
        return in_array($keyword, self::SEMI_RESERVED_KEYWORDS);
    }

    public static function isSuperglobal(string $name): bool
    {
        return in_array($name, self::SUPERGLOBALS);
    }

    public static function isScalarExpression(Expr $expr): bool
    {
        if (
            $expr instanceof Scalar
            || $expr instanceof Expr\ConstFetch
            || $expr instanceof Expr\ClassConstFetch
        ) {
            return true;
        }

        if ($expr instanceof Expr\BinaryOp) {
            return self::isScalarExpression($expr->left)
                && self::isScalarExpression($expr->right);
        }

        if (
            $expr instanceof Expr\BooleanNot
            || $expr instanceof Expr\BitwiseNot
            || $expr instanceof Expr\UnaryMinus
            || $expr instanceof Expr\UnaryPlus
        ) {
            return self::isScalarExpression($expr->expr);
        }

        if ($expr instanceof Expr\Ternary) {
            return self::isScalarExpression($expr->cond)
                && ($expr->if === null || self::isScalarExpression($expr->if))
                && self::isScalarExpression($expr->else);
        }

        if ($expr instanceof Expr\Array_) {
            foreach ($expr->items as $item) {
                if (
                    ($item->key !== null && ! self::isScalarExpression($item->key))
                    || ! self::isScalarExpression($item->value)
                ) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }
}
