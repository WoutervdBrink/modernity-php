<?php

use App\Catalogue\Clazz;
use App\Catalogue\Feature;
use App\Language\PhpVersionConstraint;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\Interface_;

Clazz::loadFromCatalogue('classes');

Feature::for(ClassLike::class)->rule(function (ClassLike $node): PhpVersionConstraint {
    $constraint = PhpVersionConstraint::open();

    if ($node instanceof Class_ || $node instanceof Interface_) {
        if (! empty($node->extends)) {
            $extends = is_array($node->extends) ? $node->extends : [$node->extends];
            foreach ($extends as $extend) {
                $constraint = $constraint->merge(Clazz::constraintFor($extend->name));
            }
        }
    }

    if ($node instanceof Class_ || $node instanceof Enum_) {
        foreach ($node->implements as $implement) {
            $constraint = $constraint->merge(Clazz::constraintFor($implement->name));
        }
    }

    return $constraint;
});

Feature::for(StaticCall::class)->rule(function (StaticCall $node): PhpVersionConstraint {
    if ($node->class instanceof Name) {
        return Clazz::constraintFor($node->class->name);
    }

    return PhpVersionConstraint::open();
});

Feature::for(ClassConstFetch::class)->rule(function (ClassConstFetch $node): PhpVersionConstraint {
    if ($node->class instanceof Name) {
        return Clazz::constraintFor($node->class->name);
    }

    return PhpVersionConstraint::open();
});

Feature::for(Catch_::class)->rule(function (Catch_ $node): PhpVersionConstraint {
    $constraint = PhpVersionConstraint::open();

    foreach ($node->types as $type) {
        $constraint = $constraint->merge(Clazz::constraintFor($type->name));
    }

    return $constraint;
});

Feature::for(New_::class)->rule(function (New_ $node): PhpVersionConstraint {
    if ($node->class instanceof Name) {
        return Clazz::constraintFor($node->class->name);
    }

    return PhpVersionConstraint::open();
});

Feature::for(Param::class)->rule(function (Param $node): PhpVersionConstraint {
    if ($node->type instanceof Name) {
        return Clazz::constraintFor($node->type->name);
    }

    return PhpVersionConstraint::open();
});
