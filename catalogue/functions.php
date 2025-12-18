<?php

use App\Catalogue\Feature;
use App\Catalogue\Func;
use App\Catalogue\FunctionCall;
use App\Language\PhpVersion;
use App\Language\PhpVersionConstraint;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;

Feature::for(FuncCall::class)->rule(function (FuncCall $node): PhpVersionConstraint {
    $name = $node->name instanceof Name ? $node->name->name : null;

    $call = new FunctionCall($name, null, count($node->args));

    return Func::constraintFor($call);
});

Feature::for(StaticCall::class)->rule(function (StaticCall $node): PhpVersionConstraint {
    $name = match (true) {
        $node->name instanceof Identifier => $node->name->name,
        default => null,
    };

    /** @var ?class-string $className */
    $className = $node->class instanceof Name ? $node->class->name : null;

    $call = new FunctionCall($name, $className, count($node->args));

    return Func::constraintFor($call);
});

// New functions in PHP 5.6.
Func::for('createFromMutable', 'DateTimeImmutable')->since(PhpVersion::PHP_5_6);
Func::for('gmp_root')->since(PhpVersion::PHP_5_6);
Func::for('gmp_rootrem')->since(PhpVersion::PHP_5_6);
Func::for('hash_equals')->since(PhpVersion::PHP_5_6);
Func::for('ldap_escape')->since(PhpVersion::PHP_5_6);
Func::for('ldap_modify_batch')->since(PhpVersion::PHP_5_6);
Func::for('mysqli_get_links_stats')->since(PhpVersion::PHP_5_6);
Func::for('oci_get_implicit_resultset')->since(PhpVersion::PHP_5_6);
Func::for('openssl_get_cert_locations')->since(PhpVersion::PHP_5_6);
Func::for('openssl_x509_fingerprint')->since(PhpVersion::PHP_5_6);
Func::for('openssl_spki_new')->since(PhpVersion::PHP_5_6);
Func::for('openssl_spki_verify')->since(PhpVersion::PHP_5_6);
Func::for('openssl_spki_export_challenge')->since(PhpVersion::PHP_5_6);
Func::for('openssl_spki_export')->since(PhpVersion::PHP_5_6);
Func::for('pg_connect_poll')->since(PhpVersion::PHP_5_6);
Func::for('pg_consume_input')->since(PhpVersion::PHP_5_6);
Func::for('pg_flush')->since(PhpVersion::PHP_5_6);
Func::for('pg_socket')->since(PhpVersion::PHP_5_6);
Func::for('pgsqlGetNotify', 'PDO')->since(PhpVersion::PHP_5_6);
Func::for('pgsqlGetPid', 'PDO')->since(PhpVersion::PHP_5_6);
Func::for('session_abort')->since(PhpVersion::PHP_5_6);
Func::for('session_reset')->since(PhpVersion::PHP_5_6);
Func::for('setPassword', 'ZipArchive')->since(PhpVersion::PHP_5_6);

// New phpdbg_ functions in PHP 5.6.
Func::for('phpdbg_break_file')->since(PhpVersion::PHP_5_6);
Func::for('phpdbg_break_function')->since(PhpVersion::PHP_5_6);
Func::for('phpdbg_break_method')->since(PhpVersion::PHP_5_6);
Func::for('phpdbg_break_next')->since(PhpVersion::PHP_5_6);
Func::for('phpdbg_clear')->since(PhpVersion::PHP_5_6);
Func::for('phpdbg_color')->since(PhpVersion::PHP_5_6);
Func::for('phpdbg_end_oplog')->since(PhpVersion::PHP_5_6);
Func::for('phpdbg_exec')->since(PhpVersion::PHP_5_6);
Func::for('phpdbg_get_executable')->since(PhpVersion::PHP_5_6);
Func::for('phpdbg_prompt')->since(PhpVersion::PHP_5_6);
Func::for('phpdbg_start_oplog')->since(PhpVersion::PHP_5_6);
