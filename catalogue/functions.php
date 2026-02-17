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
use PhpParser\Node\Scalar\Float_;
use PhpParser\Node\Scalar\Int_;
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

// Changed functions in PHP 7.1.
// file_get_contents() now accepts a negative seek offset if the stream is seekable.
Func::for('getopt')->arguments(fn (int $args): bool => $args > 2, since: PhpVersion::PHP_7_1);
Func::for('getenv')->arguments(fn (int $args): bool => $args === 0, since: PhpVersion::PHP_7_1);
Func::for('get_headers')->arguments(fn (int $args): bool => $args > 2, since: PhpVersion::PHP_7_1);
Func::for('long2ip')->argumentType(0, String_::class, until: PhpVersion::PHP_7_0)
    ->argumentType(0, Int_::class, since: PhpVersion::PHP_7_1);
// mb_ereg() now rejects illegal byte sequences.
// mb_ereg_replace() now rejects illegal byte sequences.
Func::for('openssl_decrypt')->arguments(fn (int $args): bool => $args > 5, since: PhpVersion::PHP_7_1);
Func::for('openssl_encrypt')->arguments(fn (int $args): bool => $args > 5, since: PhpVersion::PHP_7_1);
// output_reset_rewrite_vars() no longer resets session URL rewrite variables.
// parse_url() is now more restrictive and supports RFC3986.
// PDO::lastInsertId() for PostgreSQL will now trigger an error when nextval has not been called for the current session (the postgres connection).
Func::for('pg_last_notice')->arguments(fn (int $args): bool => $args > 1, since: PhpVersion::PHP_7_1);
Func::for('pg_fetch_all')->arguments(fn (int $args): bool => $args > 1, since: PhpVersion::PHP_7_1);
Func::for('pg_select')->arguments(fn (int $args): bool => $args > 3, since: PhpVersion::PHP_7_1);
// session_start() now returns false and no longer initializes $_SESSION when it failed to start the session.
// tempnam() now emits a notice when falling back to the system's temp directory.
Func::for('unpack')->arguments(fn (int $args): bool => $args > 2, since: PhpVersion::PHP_7_1);

// Changed functions in PHP 7.2.
Func::for('count')->argumentIsNull(0, until: PhpVersion::PHP_7_1);
Func::for('count')->argumentType(0, Int_::class, until: PhpVersion::PHP_7_1);
Func::for('count')->argumentType(0, Float_::class, until: PhpVersion::PHP_7_1);
Func::for('count')->argumentType(0, String_::class, until: PhpVersion::PHP_7_1);
Func::for('get_class')->argumentIsNull(0, until: PhpVersion::PHP_7_1);
Func::for('mail')->argumentType(3, Array_::class, since: PhpVersion::PHP_7_2);
Func::for('mb_send_mail')->argumentType(3, Array_::class, since: PhpVersion::PHP_7_2);
Func::for('sizeof')->argumentIsNull(0, until: PhpVersion::PHP_7_1);
Func::for('sizeof')->argumentType(0, Int_::class, until: PhpVersion::PHP_7_1);
Func::for('sizeof')->argumentType(0, Float_::class, until: PhpVersion::PHP_7_1);
Func::for('sizeof')->argumentType(0, String_::class, until: PhpVersion::PHP_7_1);

// Changed functions in PHP 7.3.
Func::for('array_push')->arguments(fn (int $args): bool => $args === 1, since: PhpVersion::PHP_7_3);
Func::for('array_unshift')->arguments(fn (int $args): bool => $args === 1, since: PhpVersion::PHP_7_3);
Func::for('bscale')
    ->arguments(fn (int $args): bool => $args === 0, since: PhpVersion::PHP_7_3)
    ->argumentIsNull(0, since: PhpVersion::PHP_7_3);
Func::for('ldap_add')->arguments(fn (int $args): bool => $args >= 4, since: PhpVersion::PHP_7_3);
Func::for('ldap_mod_replace')->arguments(fn (int $args): bool => $args >= 4, since: PhpVersion::PHP_7_3);
Func::for('ldap_mod_add')->arguments(fn (int $args): bool => $args >= 4, since: PhpVersion::PHP_7_3);
Func::for('ldap_mod_del')->arguments(fn (int $args): bool => $args >= 4, since: PhpVersion::PHP_7_3);
Func::for('ldap_rename')->arguments(fn (int $args): bool => $args >= 6, since: PhpVersion::PHP_7_3);
Func::for('ldap_compare')->arguments(fn (int $args): bool => $args >= 5, since: PhpVersion::PHP_7_3);
Func::for('ldap_delete')->arguments(fn (int $args): bool => $args >= 3, since: PhpVersion::PHP_7_3);
Func::for('ldap_modify_batch')->arguments(fn (int $args): bool => $args >= 4, since: PhpVersion::PHP_7_3);
Func::for('ldap_search')->arguments(fn (int $args): bool => $args >= 9, since: PhpVersion::PHP_7_3);
Func::for('ldap_list')->arguments(fn (int $args): bool => $args >= 9, since: PhpVersion::PHP_7_3);
Func::for('ldap_read')->arguments(fn (int $args): bool => $args >= 9, since: PhpVersion::PHP_7_3);
Func::for('ldap_parse_result')->arguments(fn (int $args): bool => $args >= 7, since: PhpVersion::PHP_7_3);
Func::for('setcookie')->argumentType(2, Array_::class, since: PhpVersion::PHP_7_3);
Func::for('setrawcookie')->argumentType(2, Array_::class, since: PhpVersion::PHP_7_3);
Func::for('session_set_cookie_params')->argumentType(0, Array_::class, since: PhpVersion::PHP_7_3);

// Changed functions in PHP 7.4.
Func::for('array_merge')->arguments(fn (int $args): bool => $args === 0, since: PhpVersion::PHP_7_4);
Func::for('array_merge_recursive')->arguments(fn (int $args): bool => $args === 0, since: PhpVersion::PHP_7_4);
Func::for('mb_ereg_replace')->argumentType(0, Int_::class, until: PhpVersion::PHP_7_3);
Func::for('password_hash')->argumentIsNull(1, since: PhpVersion::PHP_7_4);
Func::for('password_needs_rehash')->argumentIsNull(1, since: PhpVersion::PHP_7_4);
Func::for('preg_replace_callback')->arguments(fn (int $args): bool => $args >= 6, since: PhpVersion::PHP_7_4);
Func::for('preg_replace_callback_array')->arguments(fn (int $args): bool => $args >= 5, since: PhpVersion::PHP_7_4);
Func::for('proc_open')->argumentType(0, Array_::class, since: PhpVersion::PHP_7_4);
Func::for('strip_tags')->argumentType(1, Array_::class, since: PhpVersion::PHP_7_4);
