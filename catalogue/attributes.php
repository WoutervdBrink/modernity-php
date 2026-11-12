<?php

use App\Services\Analysis\Php\Catalogue\Attribute as AttributeCatalogue;
use App\Services\Analysis\Php\Catalogue\Feature;
use App\Services\Analysis\Php\Language\PhpVersionConstraint;
use PhpParser\Node\Attribute;

AttributeCatalogue::loadFromCatalogue('attributes');

Feature::for(Attribute::class)->rule(function (Attribute $node): PhpVersionConstraint {
    $name = $node->name->name;

    if ($name[0] === '\\') {
        $name = substr($name, 1);
    }

    return AttributeCatalogue::constraintFor($name);
});
