<?php

use App\Catalogue\Feature;
use App\Language\PhpVersion;
use PhpParser\Node;

Feature::for(Node\Arg::class)->sinceWhen(function (Node\Arg $node): ?PhpVersion {
    // As of PHP 5.6, arrays and traversable objects can be unpacked when calling functions.
    // https://www.php.net/manual/en/migration56.new-features.php
    if ($node->unpack) {
        return PhpVersion::PHP_5_6;
    }

    // Named arguments were introduced in PHP 8.0.
    if (! empty($node->name)) {
        return PhpVersion::PHP_8_0;
    }

    return null;
});
Feature::for(Node\ArrayItem::class)->sinceWhen(function (Node\ArrayItem $node): ?PhpVersion {
    // https://www.php.net/manual/en/migration74.new-features.php#migration74.new-features.core.unpack-inside-array
    if ($node->unpack) {
        return PhpVersion::PHP_7_4;
    }

    return null;
});
Feature::for(Node\Attribute::class);
Feature::for(Node\AttributeGroup::class);
Feature::for(Node\ClosureUse::class);
Feature::for(Node\Const_::class)->sinceWhen(function (Node\Const_ $node): ?PhpVersion {
    // As of PHP 5.6, scalar expressions are allowed in constant declarations.
    // If $node->value is instanceof Scalar, then it is a scalar, *not* a scalar expression.

    if (! $node->value instanceof Node\Scalar) {
        // As of PHP 8.1, 'new' initializers can be used in defaults of const values.
        if ($node->value instanceof Node\Expr\New_) {
            return PhpVersion::PHP_8_1;
        }

        return PhpVersion::PHP_5_6;
    }

    // The expression is a scalar value; it has no minimum version.
    return null;
});
Feature::for(Node\DeclareItem::class);
Feature::for(Node\Identifier::class);
Feature::for(Node\InterpolatedStringPart::class);
Feature::for(Node\IntersectionType::class);
Feature::for(Node\MatchArm::class);
Feature::for(Node\Name::class);
Feature::for(Node\NullableType::class)->since(PhpVersion::PHP_7_1);
Feature::for(Node\Param::class)->sinceWhen(function (Node\Param $node): ?PhpVersion {
    // As of PHP 5.6, scalar expressions are allowed in default function arguments.
    if (! empty($node->default) && ! $node->default instanceof Node\Scalar) {
        return PhpVersion::PHP_5_6;
    }

    // The variadic operator was added in PHP 5.6.
    if ($node->variadic) {
        return PhpVersion::PHP_5_6;
    }

    return null;
});
Feature::for(Node\PropertyHook::class);
Feature::for(Node\PropertyItem::class)->sinceWhen(function (Node\PropertyItem $node): ?PhpVersion {
    // As of PHP 5.6, scalar expressions are allowed in default property values.
    if (! empty($node->default) && ! $node->default instanceof Node\Scalar) {
        return PhpVersion::PHP_5_6;
    }

    return null;
});
Feature::for(Node\StaticVar::class)->sinceWhen(function (Node\StaticVar $node): ?PhpVersion {
    // As of PHP 8.1, new expressions are allowed in defaults of static variables.
    if ($node->default instanceof Node\Expr\New_) {
        return PhpVersion::PHP_8_1;
    }

    return null;
});
Feature::for(Node\UnionType::class);
Feature::for(Node\UseItem::class)->sinceWhen(function (Node\UseItem $node): PhpVersion {
    if ($node->type === Node\Stmt\Use_::TYPE_CONSTANT || $node->type === Node\Stmt\Use_::TYPE_FUNCTION) {
        return PhpVersion::PHP_5_6;
    }

    return PhpVersion::PHP_5_3;
});
Feature::for(Node\VariadicPlaceholder::class);
