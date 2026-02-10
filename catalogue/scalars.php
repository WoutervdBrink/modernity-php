<?php

use App\Catalogue\Feature;
use App\Language\PhpVersion;
use PhpParser\Node;

Feature::for(Node\Scalar\Int_::class)
    ->sinceWhen(function (Node\Scalar\Int_ $node): ?PhpVersion {
        /** @var string $rawValue */
        $rawValue = $node->getAttribute('rawValue', '');
        $kind = $node->getAttribute('kind', Node\Scalar\Int_::KIND_DEC);

        // As of PHP 8.1, octal numbers can be specified with the 0o or 0O prefix.
        // https://www.php.net/manual/en/language.types.integer.php#language.types.integer.syntax
        if (
            $kind === Node\Scalar\Int_::KIND_OCT &&
            strtolower($rawValue[1]) === 'o'
        ) {
            return PhpVersion::PHP_8_0;
        }

        // As of PHP 7.4, integer literals may contain underscores between digits.
        // https://www.php.net/manual/en/language.types.integer.php#language.types.integer.syntax
        if (str_contains($rawValue, '_')) {
            return PhpVersion::PHP_7_4;
        }

        // The binary notation for integers was introduced in PHP 5.4.
        if ($kind === Node\Scalar\Int_::KIND_BIN) {
            return PhpVersion::PHP_5_4;
        }

        return null;
    })->untilWhen(function (Node\Scalar\Int_ $node): ?PhpVersion {
        // As of PHP 7.0, integer octal literals may no longer contain invalid numbers.
        /** @var string $rawValue */
        $rawValue = $node->getAttribute('rawValue', '');
        $kind = $node->getAttribute('kind');
        if ($kind === Node\Scalar\Int_::KIND_OCT && preg_match('/[89]/', $rawValue)) {
            return PhpVersion::PHP_5_6;
        }

        return null;
    });

Feature::for(Node\Scalar\Float_::class)->sinceWhen(function (Node\Scalar\Float_ $node) {
    // As of PHP 7.4, float literals may contain underscores between digits.
    /** @var string $rawValue */
    $rawValue = $node->getAttribute('rawValue', '');
    if (str_contains($rawValue, '_')) {
        return PhpVersion::PHP_7_4;
    }

    return null;
});

Feature::for(Node\Scalar\String_::class)->sinceWhen(function (Node\Scalar\String_ $node) {
    if (preg_match('/u\{([0-9a-fA-F]+)}/', $node->getAttribute('rawValue'))) {
        return PhpVersion::PHP_7_0;
    }

    return null;
});

// __DIR__ and __NAMESPACE__ were added in 5.3.
Feature::for(Node\Scalar\MagicConst\Dir::class)->since(PhpVersion::PHP_5_3);
Feature::for(Node\Scalar\MagicConst\Namespace_::class)->since(PhpVersion::PHP_5_3);

// __TRAIT__ was added in 5.4.
Feature::for(Node\Scalar\MagicConst\Trait_::class)->since(PhpVersion::PHP_5_4);
