<?php

use App\Catalogue\Constant;
use App\Catalogue\Feature;
use App\Language\PhpVersion;
use App\Language\PhpVersionConstraint;
use PhpParser\Node\Expr\ConstFetch;

Feature::for(ConstFetch::class)->rule(function (ConstFetch $node): PhpVersionConstraint {
    return Constant::constraintFor($node->name->name);
});

$fp = fopen(resource_path('catalogue/constants.csv'), 'r');

if ($fp === false) {
    throw new RuntimeException('Unable to open constants csv file.');
}

fgetcsv($fp);

$i = 1;
while (($row = fgetcsv($fp)) !== false) {
    if (count($row) !== 3) {
        throw new UnexpectedValueException('Row '.$i.' in the constants database should have 3 columns; it has '.count($row).'.');
    }

    [$name, $since, $until] = $row;

    $const = Constant::for($name);

    if (! empty($since)) {
        $const->since(PhpVersion::fromVersionString($since));
    }
    if (! empty($until)) {
        $const->until(PhpVersion::fromVersionString($until));
    }
}
