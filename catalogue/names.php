<?php

use App\Services\Analysis\Php\Catalogue\Feature;
use App\Services\Analysis\Php\Language\PhpVersion;
use PhpParser\Node;

Feature::for(Node\Name\FullyQualified::class)->since(PhpVersion::PHP_5_3);
Feature::for(Node\Name\Relative::class)->since(PhpVersion::PHP_5_3);
