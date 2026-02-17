<?php

use App\Catalogue\Feature;
use App\Inspectors\FunctionOrMethodInspector;
use App\Language\PhpVersion;
use App\Language\Quirks;
use PhpParser\Node;
use PhpParser\Node\Expr\List_;

Feature::for(Node\Expr\ArrayDimFetch::class)
    ->sinceWhen(function (Node\Expr\ArrayDimFetch $node): ?PhpVersion {
        // Negative string offsets (PHP 7.1)
        if ($node->var instanceof Node\Scalar\String_ && $node->dim instanceof Node\Expr\UnaryMinus) {
            return PhpVersion::PHP_7_1;
        }

        // https://wiki.php.net/rfc/constdereference
        if ($node->var instanceof Node\Expr\Array_ || $node->var instanceof Node\Scalar\String_) {
            return PhpVersion::PHP_5_5;
        }

        // https://wiki.php.net/rfc/functionarraydereferencing
        if ($node->var instanceof Node\Expr\CallLike) {
            return PhpVersion::PHP_5_4;
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

/**
 * Returns <code>true</code> if any of the variables in a list are assigned by reference.
 */
function hasReferenceAssignment(List_ $node): bool
{
    foreach ($node->items as $item) {
        if ($item !== null) {
            if ($item->byRef) {
                return true;
            }
            if ($item->value instanceof List_) {
                return hasReferenceAssignment($item->value);
            }
        }
    }

    return false;
}

Feature::for(Node\Expr\Assign::class)
    ->sinceWhen(function (Node\Expr\Assign $node): ?PhpVersion {
        // Symmetric array destructuring ([$foo, $bar] = $baz) was introduced in PHP 7.1.
        if ($node->var instanceof List_ && $node->var->getAttribute('kind', List_::KIND_LIST) === List_::KIND_ARRAY) {
            return hasReferenceAssignment($node->var) ? PhpVersion::PHP_7_3 : PhpVersion::PHP_7_1;
        }

        return null;
    })
    ->untilWhen(function (Node\Expr\Assign $node): ?PhpVersion {
        if ($node->var instanceof Node\Expr\Variable && $node->var->name === 'this') {
            return PhpVersion::PHP_7_0;
        }

        return null;
    });
Feature::for(Node\Expr\AssignOp::class)->untilWhen(function (Node\Expr\AssignOp $node): ?PhpVersion {
    if ($node->var instanceof Node\Expr\Variable && $node->var->name === 'this') {
        return PhpVersion::PHP_7_0;
    }

    if ($node instanceof Node\Expr\AssignOp\ShiftLeft || $node instanceof Node\Expr\AssignOp\ShiftRight) {
        if ($node->expr instanceof Node\Expr\UnaryMinus && $node->expr->expr instanceof Node\Scalar\Int_) {
            // As of PHP 7.0, negative bitshifts are no longer allowed.
            // We can only detect this for literals.
            return PhpVersion::PHP_5_6;
        }
    }

    return null;
});
Feature::for(Node\Expr\AssignRef::class)->untilWhen(function (Node\Expr\AssignRef $node): ?PhpVersion {
    // https://www.php.net/manual/en/migration70.incompatible.php#migration70.incompatible.other.new-by-ref
    if ($node->expr instanceof Node\Expr\New_) {
        return PhpVersion::PHP_5_6;
    }

    return null;
});
Feature::for(Node\Expr\BinaryOp::class)->untilWhen(function (Node\Expr\BinaryOp $node): ?PhpVersion {
    if ($node instanceof Node\Expr\BinaryOp\ShiftLeft || $node instanceof Node\Expr\BinaryOp\ShiftRight) {
        if ($node->right instanceof Node\Expr\UnaryMinus && $node->right->expr instanceof Node\Scalar\Int_) {
            // As of PHP 7.0, negative bitshifts are no longer allowed.
            // We can only detect this for literals.
            return PhpVersion::PHP_5_6;
        }
    }

    return null;
});
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
    ->untilWhen(function (Node\Expr\Closure $node): ?PhpVersion {
        // Lexically bound variables cannot reuse names as of PHP 7.1.

        $parameterNames = [];

        foreach ($node->params as $param) {
            if ($param->var instanceof Node\Expr\Variable) {
                $parameterNames[] = $param->var->name;
            }
        }

        foreach ($node->uses as $use) {
            if (is_string($use->var->name)) {
                if (
                    $use->var->name === 'this' ||
                    Quirks::isSuperglobal($use->var->name) ||
                    in_array($use->var->name, $parameterNames)
                ) {
                    return PhpVersion::PHP_7_0;
                }
            }
        }

        return null;
    })
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

    // As of PHP 7.3, literals are allowed as the first operand.
    if (! $node->expr instanceof Node\Expr\Variable) {
        return PhpVersion::PHP_7_3;
    }

    return null;
});
Feature::for(Node\Expr\Isset_::class);
Feature::for(List_::class)
    ->sinceWhen(function (List_ $node): ?PhpVersion {
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
    ->untilWhen(function (List_ $node): ?PhpVersion {
        // As of PHP 7.0, list() constructs can no longer be empty.
        if (! empty($node->items) && $node->items[0] === null) {
            return PhpVersion::PHP_5_6;
        }

        return null;
    });
Feature::for(Node\Expr\Match_::class);
Feature::for(Node\Expr\MethodCall::class)->sinceWhen(function (Node\Expr\MethodCall $node): ?PhpVersion {
    // As of PHP 7.0, class members can be accessed on cloning.
    if ($node->var instanceof Node\Expr\Clone_) {
        return PhpVersion::PHP_7_0;
    }

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
Feature::for(Node\Expr\PropertyFetch::class)->sinceWhen(function (Node\Expr\PropertyFetch $node) {
    // As of PHP 7.0, class members can be accessed on cloning.
    if ($node->var instanceof Node\Expr\Clone_) {
        return PhpVersion::PHP_7_0;
    }

    return null;
});
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
Feature::for(Node\Expr\AssignOp\Coalesce::class)->since(PhpVersion::PHP_7_4); // https://www.php.net/manual/en/migration74.new-features.php#migration74.new-features.core.null-coalescing-assignment-operator
Feature::for(Node\Expr\AssignOp\Pow::class)->since(PhpVersion::PHP_5_6); // https://wiki.php.net/rfc/pow-operator
Feature::for(Node\Expr\BinaryOp\Coalesce::class)->since(PhpVersion::PHP_7_0); // https://wiki.php.net/rfc/isset_ternary
Feature::for(Node\Expr\BinaryOp\Pow::class)->since(PhpVersion::PHP_5_6); // https://wiki.php.net/rfc/pow-operator
Feature::for(Node\Expr\BinaryOp\Spaceship::class)->since(PhpVersion::PHP_7_0); // https://wiki.php.net/rfc/combined-comparison-operator
