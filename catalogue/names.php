<?php

use App\Catalogue\Feature;
use App\Language\PhpVersion;
use PhpParser\Node;

Feature::for(Node\Name\FullyQualified::class)->since(PhpVersion::PHP_5_3);
Feature::for(Node\Name\Relative::class)->since(PhpVersion::PHP_5_3);
