<?php

use App\Catalogue\Constant;
use App\Catalogue\Feature;
use App\Language\PhpVersionConstraint;
use PhpParser\Node\Expr\ConstFetch;

Constant::loadFromCatalogue('constants');

Feature::for(ConstFetch::class)->rule(function (ConstFetch $node): PhpVersionConstraint {
    return Constant::constraintFor($node->name->name);
});
