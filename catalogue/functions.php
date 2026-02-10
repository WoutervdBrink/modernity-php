<?php

use App\Catalogue\Feature;
use App\Catalogue\Func;
use App\Catalogue\FunctionCall;
use App\Language\PhpVersion;
use App\Language\PhpVersionConstraint;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;

Func::loadFromCatalogue('functions');

Feature::for(FuncCall::class)->rule(function (FuncCall $node): PhpVersionConstraint {
    $name = $node->name instanceof Name ? $node->name->name : null;

    $call = new FunctionCall($name, null, $node->args);

    return Func::constraintForFunctionCall($call);
});

Feature::for(StaticCall::class)->rule(function (StaticCall $node): PhpVersionConstraint {
    $name = match (true) {
        $node->name instanceof Identifier => $node->name->name,
        default => null,
    };

    /** @var ?class-string $className */
    $className = $node->class instanceof Name ? $node->class->name : null;

    $call = new FunctionCall($name, $className, $node->args);

    return Func::constraintForFunctionCall($call);
});

// Changed functions in PHP 5.6.
Func::for('stream_socket_enable_crypto')->arguments(fn (int $args): bool => $args === 2, since: PhpVersion::PHP_5_6);

// Changed functions in PHP 7.0.
Func::for('assert')->argumentType(0, String_::class, sinceOtherwise: PhpVersion::PHP_7_0);
// debug_zval_dump() now prints "int" instead of "long", and "float" instead of "double".
Func::for('define')->argumentType(1, Array_::class, since: PhpVersion::PHP_7_0);
Func::for('dirname')->arguments(fn (int $args): bool => $args > 1, since: PhpVersion::PHP_7_0);
//  exec(), system() and passthru() functions have NULL byte protection now.
// getrusage() is now supported on Windows.
Func::for('gmmktime')->arguments(fn (int $args): bool => $args > 6, until: PhpVersion::PHP_5_6);
Func::for('mktime')->arguments(fn (int $args): bool => $args > 6, until: PhpVersion::PHP_5_6);
Func::for('password_hash')->option(2, 'salt', until: PhpVersion::PHP_5_6);
// preg_replace() function no longer supports "\e" (PREG_REPLACE_EVAL). preg_replace_callback() should be used instead.
Func::for('session_start')->arguments(fn (int $args): bool => $args > 0, since: PhpVersion::PHP_7_0);
Func::for('setlocale')->argumentType(0, String_::class, until: PhpVersion::PHP_5_6);
// shmop_open() now returns a resource instead of an int, which has to be passed to shmop_size(), shmop_write(), shmop_read(), shmop_close() and shmop_delete().
// substr() and iconv_substr() now return an empty string, if string is equal to start characters long.
Func::for('unserialize')->option(1, 'allowed_classes', since: PhpVersion::PHP_7_0);
//  xml_parser_free() is no longer sufficient to free the parser resource, ...
