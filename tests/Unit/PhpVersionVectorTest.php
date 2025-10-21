<?php

use App\Language\PhpVersion;
use App\Language\PhpVersionVector;

describe('zero', function () {
    it('starts with zero for every PHP version', function () {
        $empty = PhpVersionVector::zero();

        expect($empty)->toHaveCount(PhpVersion::count());

        foreach ($empty as $value) {
            expect($value)->toBe(0.0);
        }
    });
});

describe('of', function () {
    it('accepts values and stores them', function () {
        $vector = PhpVersionVector::of(padVersionFloats(1.0, 2.0, 3.0));
        $versions = PhpVersion::orderedCases();

        expect($vector[$versions[0]])->toBe(1.0)
            ->and($vector[$versions[1]])->toBe(2.0)
            ->and($vector[$versions[2]])->toBe(3.0);
    });
});

describe('array access', function () {
    it('iterates in ordered index order', function () {
        $vector = PhpVersionVector::zero();

        $keys = [];

        /**
         * @var PhpVersion $key
         * @var float $value
         */
        foreach ($vector as $key => $value) {
            $keys[] = $key;
        }

        expect($keys)->toBe(PhpVersion::orderedCases());
    });
});
