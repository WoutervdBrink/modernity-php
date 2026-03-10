<?php

use App\Catalogue\Attribute as AttributeCatalogue;
use App\Catalogue\Feature;
use App\Language\PhpVersionConstraint;
use PhpParser\Node\Attribute as AttributeNode;

AttributeCatalogue::loadFromCatalogue('attributes');

Feature::for(AttributeNode::class)->rule(function (AttributeNode $node): PhpVersionConstraint {
    $name = $node->name->name;

    if ($name[0] === '\\') {
        $name = substr($name, 1);
    }

    return AttributeCatalogue::constraintFor($name);
});
