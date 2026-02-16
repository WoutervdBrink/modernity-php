<?php

use App\Catalogue\Constant;
use App\Catalogue\Feature;
use App\Language\PhpVersionConstraint;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;

Constant::loadFromCatalogue('constants');

Feature::for(ConstFetch::class)->rule(function (ConstFetch $node): PhpVersionConstraint {
    return Constant::constraintFor($node->name->name);
});

Feature::for(ClassConstFetch::class)->rule(function (ClassConstFetch $node): PhpVersionConstraint {
    if ($node->name instanceof Identifier && $node->class instanceof Name) {
        return Constant::constraintForWithClass($node->name->name, $node->class->name);
    }

    return PhpVersionConstraint::open();
});
