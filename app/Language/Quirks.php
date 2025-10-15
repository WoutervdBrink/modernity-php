<?php

namespace App\Language;

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
        'print',
        'private',
        'protected',
        'public',
        'require',
        'require_once',
        'return',
        'self parent',
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
}
