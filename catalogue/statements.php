<?php

use App\Feature;
use App\Inspectors\FunctionOrMethodInspector;
use App\Language\PhpVersion;
use App\Language\Quirks;
use PhpParser\Modifiers;
use PhpParser\Node;

Feature::for(Node\Stmt\Block::class);
Feature::for(Node\Stmt\Break_::class)->untilWhen(function (Node\Stmt\Break_ $node): ?PhpVersion {
    // As of PHP 7.0, break statements no longer allow their argument to be a constant.
    if ($node->num instanceof Node\Expr\ConstFetch) {
        return PhpVersion::PHP_5_6;
    }

    return null;
});
Feature::for(Node\Stmt\Case_::class);
Feature::for(Node\Stmt\Catch_::class)->sinceWhen(function (Node\Stmt\Catch_ $node): ?PhpVersion {
    // The variable was required prior to PHP 8.0.0.
    // https://www.php.net/manual/en/language.exceptions.php#language.exceptions.catch
    if (empty($node->var)) {
        return PhpVersion::PHP_8_0;
    }

    // As of PHP 7.1, multiple exception types can be caught
    // https://wiki.php.net/rfc/multiple-catch
    if (count($node->types) > 1) {
        return PhpVersion::PHP_7_1;
    }

    return null;
});
Feature::for(Node\Stmt\ClassConst::class)->sinceWhen(function (Node\Stmt\ClassConst $node): ?PhpVersion {
    // As of PHP 8.1, class constants can have the final modifier.
    // https://www.php.net/manual/en/language.oop5.constants.php
    if ($node->flags && $node->flags & Modifiers::FINAL) {
        return PhpVersion::PHP_8_1;
    }

    // As of PHP 7.1, visibility modifiers are allowed for class constants.
    // https://www.php.net/manual/en/language.oop5.constants.php
    if ($node->flags && $node->flags & Modifiers::VISIBILITY_MASK) {
        return PhpVersion::PHP_7_1;
    }

    return null;
});
Feature::for(Node\Stmt\ClassMethod::class)
    ->inspector(FunctionOrMethodInspector::class)
    // https://www.php.net/manual/en/language.oop5.changelog.php
    ->sinceWhen(function (Node\Stmt\ClassMethod $node): ?PhpVersion {
        $name = $node->name->toString();

        // As of PHP 7.0, it is allowed to use semi-reserved keywords as method names.
        // https://wiki.php.net/rfc/context_sensitive_lexer
        if (Quirks::isSemiReservedKeyword($name)) {
            return PhpVersion::PHP_7_0;
        }

        // Various magic methods were introduced in later PHP versions.
        // https://www.php.net/manual/en/language.oop5.changelog.php
        return match ($node->name->toString()) {
            '__invoke', '__callStatic' => PhpVersion::PHP_5_3,
            '__debugInfo' => PhpVersion::PHP_5_6,
            '__serialize' => PhpVersion::PHP_7_4,
            default => null,
        };
    })
    ->untilWhen(function (Node\Stmt\ClassMethod $node): ?PhpVersion {
        // As of PHP 7.2, using the __autoload magic class method is deprecated.
        // https://www.php.net/manual/en/language.oop5.changelog.php
        if ($node->name->toString() === '__autoload') {
            return PhpVersion::PHP_7_1;
        }

        return null;
    });
Feature::for(Node\Stmt\Class_::class)
    ->untilWhen(fn (Node\Stmt\Class_ $node): ?PhpVersion => match ($node->name?->toString() ?? null) {
        // As of PHP 7.0, it is not allowed to use 'bool', 'int', 'float', 'string', 'null', 'true' or 'false' as a
        // class name.
        'bool', 'int', 'float', 'string', 'null', 'true', 'false' => PhpVersion::PHP_5_6,

        // Idem for PHP 7.1 and 'void' or 'iterable'.
        'void', 'iterable' => PhpVersion::PHP_7_0,

        // Idem for PHP 7.2 and 'object'.
        'object' => PhpVersion::PHP_7_1,
        default => null,
    });
Feature::for(Node\Stmt\Const_::class);
Feature::for(Node\Stmt\Continue_::class)->untilWhen(function (Node\Stmt\Continue_ $node): ?PhpVersion {
    // As of PHP 7.0, continue statements no longer allow their argument to be a constant.
    if ($node->num instanceof Node\Expr\ConstFetch) {
        return PhpVersion::PHP_5_6;
    }

    return null;
});
Feature::for(Node\Stmt\Declare_::class);
Feature::for(Node\Stmt\Do_::class);
Feature::for(Node\Stmt\Echo_::class);
Feature::for(Node\Stmt\ElseIf_::class);
Feature::for(Node\Stmt\Else_::class);
Feature::for(Node\Stmt\EnumCase::class)->since(PhpVersion::PHP_8_1);
Feature::for(Node\Stmt\Enum_::class)->since(PhpVersion::PHP_8_1);
Feature::for(Node\Stmt\Expression::class);
Feature::for(Node\Stmt\Finally_::class)->since(PhpVersion::PHP_5_5);
Feature::for(Node\Stmt\For_::class);
Feature::for(Node\Stmt\Foreach_::class)->sinceWhen(function (Node\Stmt\Foreach_ $node): ?PhpVersion {
    // As of PHP 5.5, it is possible to unpack arrays in arrays using list() or [].
    // https://www.php.net/manual/en/control-structures.foreach.php#control-structures.foreach.list
    if ($node->valueVar instanceof Node\Expr\List_) {
        // However, using the [] syntax was only introduced in PHP 7.1, so we split here.
        $kind = $node->valueVar->getAttribute('kind');

        return match ($kind) {
            Node\Expr\List_::KIND_LIST => PhpVersion::PHP_5_5,
            Node\Expr\List_::KIND_ARRAY => PhpVersion::PHP_7_1,
            default => throw new LogicException('Unknown list kind!')
        };
    }

    return null;
});
Feature::for(Node\Stmt\Function_::class)->inspector(FunctionOrMethodInspector::class);
Feature::for(Node\Stmt\Global_::class);
Feature::for(Node\Stmt\Goto_::class)->since(PhpVersion::PHP_5_3);
Feature::for(Node\Stmt\GroupUse::class)->since(PhpVersion::PHP_7_0);
Feature::for(Node\Stmt\HaltCompiler::class);
Feature::for(Node\Stmt\If_::class);
Feature::for(Node\Stmt\InlineHTML::class);
Feature::for(Node\Stmt\Interface_::class)
    ->untilWhen(fn (Node\Stmt\Interface_ $node): ?PhpVersion => match ($node->name?->toString() ?? null) {
        // As of PHP 7.0, it is not allowed to use 'bool', 'int', 'float', 'string', 'null', 'true' or 'false' as an
        // interface name.
        'bool', 'int', 'float', 'string', 'null', 'true', 'false' => PhpVersion::PHP_5_6,

        // Idem for PHP 7.1 and 'void' or 'iterable'.
        'void', 'iterable' => PhpVersion::PHP_7_0,

        // Idem for PHP 7.2 and 'object'.
        'object' => PhpVersion::PHP_7_1,
        default => null,
    });
Feature::for(Node\Stmt\Label::class)->since(PhpVersion::PHP_5_3);
Feature::for(Node\Stmt\Namespace_::class)->since(PhpVersion::PHP_5_3);
Feature::for(Node\Stmt\Nop::class);
Feature::for(Node\Stmt\Property::class)->sinceWhen(function (Node\Stmt\Property $node): ?PhpVersion {
    // The readonly modifier was introduced in PHP 8.1.
    // https://www.php.net/manual/en/language.oop5.properties.php#language.oop5.properties.readonly-properties
    if ($node->flags & Modifiers::READONLY) {
        return PhpVersion::PHP_8_1;
    }

    // As of PHP 7.4, properties can be typed.
    // https://www.php.net/manual/en/language.oop5.properties.php#language.oop5.properties.typed-properties
    if (! empty($node->type)) {
        return PhpVersion::PHP_7_4;
    }

    return null;
});
Feature::for(Node\Stmt\Return_::class);
Feature::for(Node\Stmt\Static_::class);
Feature::for(Node\Stmt\Switch_::class)->untilWhen(function (Node\Stmt\Switch_ $node): ?PhpVersion {
    // As of PHP 7.0, it is no longer possible to define two or more default blocks in a switch statement.
    $foundDefault = false;

    foreach ($node->cases as $case) {
        if ($case->cond === null) {
            if ($foundDefault) {
                return PhpVersion::PHP_5_6;
            } else {
                $foundDefault = true;
            }
        }
    }

    return null;
});
Feature::for(Node\Stmt\TraitUse::class)->since(PhpVersion::PHP_5_4);
Feature::for(Node\Stmt\Trait_::class)
    ->since(PhpVersion::PHP_5_4)
    ->untilWhen(fn (Node\Stmt\Trait_ $node): ?PhpVersion => match ($node->name?->toString() ?? null) {
        // As of PHP 7.0, it is not allowed to use 'bool', 'int', 'float', 'string', 'null', 'true' or 'false' as a
        // trait name.
        'bool', 'int', 'float', 'string', 'null', 'true', 'false' => PhpVersion::PHP_5_6,

        // Idem for PHP 7.1 and 'void' or 'iterable'.
        'void', 'iterable' => PhpVersion::PHP_7_0,

        // Idem for PHP 7.2 and 'object'.
        'object' => PhpVersion::PHP_7_1,
        default => null,
    });
Feature::for(Node\Stmt\TryCatch::class);
Feature::for(Node\Stmt\Unset_::class);
Feature::for(Node\Stmt\Use_::class)->sinceWhen(function (Node\Stmt\Use_ $node): PhpVersion {
    // As of PHP 5.6, functions and constants can be imported using use.
    if ($node->type === Node\Stmt\Use_::TYPE_CONSTANT || $node->type === Node\Stmt\Use_::TYPE_FUNCTION) {
        return PhpVersion::PHP_5_6;
    }

    // Use was introduced in PHP 5.3.
    return PhpVersion::PHP_5_3;
});
Feature::for(Node\Stmt\While_::class);
Feature::for(Node\Stmt\TraitUseAdaptation\Alias::class)->since(PhpVersion::PHP_5_4);
Feature::for(Node\Stmt\TraitUseAdaptation\Precedence::class)->since(PhpVersion::PHP_5_4);
