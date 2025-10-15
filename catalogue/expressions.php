<?php

use App\Feature;
use App\Inspectors\FunctionOrMethodInspector;
use App\Language\PhpVersion;
use App\Language\Quirks;
use PhpParser\Node;

Feature::for(Node\Expr\ArrayDimFetch::class)
    ->sinceWhen(function (Node\Expr\ArrayDimFetch $node): ?PhpVersion {
        // https://wiki.php.net/rfc/functionarraydereferencing
        if ($node->var instanceof Node\Expr\CallLike) {
            return PhpVersion::PHP_5_4;
        }

        // https://wiki.php.net/rfc/constdereference
        if ($node->var instanceof Node\Expr\Array_ || $node->var instanceof Node\Scalar\String_) {
            return PhpVersion::PHP_5_5;
        }

        return null;
    });
Feature::for(Node\Expr\Array_::class)
    ->sinceWhen(function (Node\Expr\Array_ $node): ?PhpVersion {
        // https://wiki.php.net/rfc/shortsyntaxforarrays
        if ($node->getAttribute('kind') === Node\Expr\Array_::KIND_SHORT) {
            return PhpVersion::PHP_5_4;
        }

        return null;
    });
Feature::for(Node\Expr\ArrowFunction::class)
    ->inspector(FunctionOrMethodInspector::class)
    ->since(PhpVersion::PHP_7_4);
Feature::for(Node\Expr\Assign::class);
Feature::for(Node\Expr\AssignRef::class);
Feature::for(Node\Expr\BitwiseNot::class);
Feature::for(Node\Expr\BooleanNot::class);
Feature::for(Node\Expr\ClassConstFetch::class)
    ->sinceWhen(function (Node\Expr\ClassConstFetch $node): ?PhpVersion {
        // https://www.php.net/manual/en/language.oop5.changelog.php
        if ($node->name instanceof Node\Identifier && $node->name->name === 'class') {
            return PhpVersion::PHP_5_5;
        }

        return null;
    });
Feature::for(Node\Expr\Clone_::class);
Feature::for(Node\Expr\Closure::class)
    ->inspector(FunctionOrMethodInspector::class)
    ->since(PhpVersion::PHP_5_3);
Feature::for(Node\Expr\ConstFetch::class)->sinceWhen(function (Node\Expr\ConstFetch $node): ?PhpVersion {
    // The E_USER_DEPRECATED and E_USER_DEPRECATED constants were introduced in PHP 5.3.
    // https://wiki.php.net/rfc/e-user-deprecated-warning
    if ($node->name->toString() === 'E_USER_DEPRECATED' || $node->name->toString() === 'E_DEPRECATED') {
        return PhpVersion::PHP_5_3;
    }

    return null;
});
Feature::for(Node\Expr\Empty_::class)->sinceWhen(function (Node\Expr\Empty_ $node): ?PhpVersion {
    // Since PHP 5.5, arbitrary expression arguments are allowed inside empty().
    // https://wiki.php.net/rfc/empty_isset_exprs
    return ! $node->expr instanceof Node\Expr\Variable ? PhpVersion::PHP_5_5 : null;
});
Feature::for(Node\Expr\Error::class);
Feature::for(Node\Expr\ErrorSuppress::class);
Feature::for(Node\Expr\Eval_::class);
Feature::for(Node\Expr\Exit_::class);
Feature::for(Node\Expr\FuncCall::class);
Feature::for(Node\Expr\Include_::class);
Feature::for(Node\Expr\Instanceof_::class)->sinceWhen(function (Node\Expr\Instanceof_ $node): ?PhpVersion {
    // As of PHP 8.0.0, instanceof can now be used with arbitrary expressions.
    // https://www.php.net/instanceof
    if ($node->class instanceof Node\Expr && ! $node->class instanceof Node\Expr\Variable) {
        return PhpVersion::PHP_8_0;
    }

    return null;
});
Feature::for(Node\Expr\Isset_::class);
Feature::for(Node\Expr\List_::class)
    ->sinceWhen(function (Node\Expr\List_ $node): ?PhpVersion {
        foreach ($node->items as $item) {
            // In PHP 7.3, new syntax was introduced, allowing reference assignment using list().
            // https://wiki.php.net/rfc/list_reference_assignment
            if ($item?->byRef) {
                return PhpVersion::PHP_7_3;
            }

            // As of PHP 7.1, keys can be specified in list().
            // https://wiki.php.net/rfc/list_keys
            if ($item?->key) {
                return PhpVersion::PHP_7_1;
            }
        }

        return null;
    })
    ->untilWhen(function (Node\Expr\List_ $node): ?PhpVersion {
        // As of PHP 7.0, list() constructs can no longer be empty.
        if (! empty($node->items) && $node->items[0] === null) {
            return PhpVersion::PHP_5_6;
        }

        return null;
    });
Feature::for(Node\Expr\Match_::class);
Feature::for(Node\Expr\MethodCall::class)->sinceWhen(function (Node\Expr\MethodCall $node): ?PhpVersion {
    // As of PHP 7.0, semi-reserved keywords can be used as method names.
    // https://wiki.php.net/rfc/context_sensitive_lexer
    if (
        $node->name instanceof Node\Identifier &&
        Quirks::isSemiReservedKeyword($node->name->toString())
    ) {
        return PhpVersion::PHP_7_0;
    }

    return null;
});
Feature::for(Node\Expr\New_::class)->sinceWhen(function (Node\Expr\New_ $node): ?PhpVersion {
    // As of PHP 8.0, using new with arbitrary expressions is supported.
    // https://www.php.net/manual/en/language.oop5.basic.php#language.oop5.basic.new
    if ($node->class instanceof Node\Expr &&
        ! $node->class instanceof Node\Expr\Variable &&
        ! $node->class instanceof Node\Expr\PropertyFetch &&
        ! $node->class instanceof Node\Expr\StaticPropertyFetch &&
        ! $node->class instanceof Node\Expr\ArrayDimFetch
    ) {
        return PhpVersion::PHP_8_0;
    }

    // Anonymous classes were introduced in PHP 7.0.
    if ($node->class instanceof Node\Stmt\Class_) {
        return PhpVersion::PHP_7_0;
    }

    return null;
});
Feature::for(Node\Expr\NullsafeMethodCall::class)->since(PhpVersion::PHP_8_0);
Feature::for(Node\Expr\NullsafePropertyFetch::class)->since(PhpVersion::PHP_8_0);
Feature::for(Node\Expr\PostDec::class);
Feature::for(Node\Expr\PostInc::class);
Feature::for(Node\Expr\PreDec::class);
Feature::for(Node\Expr\PreInc::class);
Feature::for(Node\Expr\Print_::class);
Feature::for(Node\Expr\PropertyFetch::class);
Feature::for(Node\Expr\ShellExec::class);
Feature::for(Node\Expr\StaticCall::class);
Feature::for(Node\Expr\StaticPropertyFetch::class);
Feature::for(Node\Expr\Ternary::class);
Feature::for(Node\Expr\Throw_::class)->sinceWhen(function (Node\Expr\Throw_ $node) {
    $parent = $node->getAttribute('parent');

    // As of PHP 8.0.0, the throw keyword is an expression and may be used in any expression context.
    if ($parent && ! $parent instanceof Node\Stmt\Expression) {
        return PhpVersion::PHP_8_0;
    }

    return null;
});
Feature::for(Node\Expr\UnaryMinus::class);
Feature::for(Node\Expr\UnaryPlus::class);
Feature::for(Node\Expr\Variable::class)->untilWhen(function (Node\Expr\Variable $node) {
    // As of PHP 7.0, $HTTP_RAW_POST_DATA is no longer available.
    if ($node->name === 'HTTP_RAW_POST_DATA') {
        return PhpVersion::PHP_5_6;
    }

    return null;
});
Feature::for(Node\Expr\YieldFrom::class)->since(PhpVersion::PHP_7_0); // https://wiki.php.net/rfc/generator-delegation
Feature::for(Node\Expr\Yield_::class)->since(PhpVersion::PHP_5_5); // https://www.php.net/manual/en/language.generators.overview.php
Feature::for(Node\Expr\Cast\Unset_::class)->until(PhpVersion::PHP_7_4); // https://www.php.net/manual/en/language.types.null.php#language.types.null.casting
Feature::for(Node\Expr\AssignOp\Coalesce::class)->since(PhpVersion::PHP_7_0); // https://wiki.php.net/rfc/isset_ternary
Feature::for(Node\Expr\AssignOp\Pow::class)->since(PhpVersion::PHP_5_6); // https://wiki.php.net/rfc/pow-operator
Feature::for(Node\Expr\BinaryOp\Coalesce::class)->since(PhpVersion::PHP_7_0); // https://wiki.php.net/rfc/isset_ternary
Feature::for(Node\Expr\BinaryOp\Pow::class)->since(PhpVersion::PHP_5_6); // https://wiki.php.net/rfc/pow-operator
Feature::for(Node\Expr\BinaryOp\Spaceship::class)->since(PhpVersion::PHP_7_0); // https://wiki.php.net/rfc/combined-comparison-operator
