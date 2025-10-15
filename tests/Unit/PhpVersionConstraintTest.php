<?php

namespace Tests\Unit;

use App\Language\PhpVersion;
use App\Language\PhpVersionConstraint;
use InvalidArgumentException;

dataset('version sets', [
    // ?Min, ?max
    [null, PhpVersion::PHP_5_2],
    [PhpVersion::PHP_5_2, null],
    [PhpVersion::PHP_5_1, PhpVersion::PHP_5_2],
    [PhpVersion::PHP_7_0, PhpVersion::PHP_8_0],
    [PhpVersion::PHP_7_0, PhpVersion::PHP_8_1],
]);

describe('constructor', function () {
    it('creates since', function () {
        $constraint = PhpVersionConstraint::since(PhpVersion::PHP_7_0);

        expect($constraint->min)->toBe(PhpVersion::PHP_7_0)
            ->and($constraint->max)->toBeNull();
    });

    it('creates until', function () {
        $constraint = PhpVersionConstraint::until(PhpVersion::PHP_7_0);

        expect($constraint->min)->toBeNull()
            ->and($constraint->max)->toBe(PhpVersion::PHP_7_0);
    });

    it('defaults to open when since is null', function () {
        $constraint = PhpVersionConstraint::since(null);

        expect($constraint)->toBe(PhpVersionConstraint::open());
    });

    it('defaults to open when until is null', function () {
        $constraint = PhpVersionConstraint::until(null);

        expect($constraint)->toBe(PhpVersionConstraint::open());
    });

    it('defaults to open when between values are null', function () {
        $constraint = PhpVersionConstraint::between(null, null);

        expect($constraint)->toEqual(PhpVersionConstraint::open());
    });

    it('validates between ordering', function () {
        PhpVersionConstraint::between(PhpVersion::PHP_7_0, PhpVersion::PHP_5_6);
    })->throws(InvalidArgumentException::class);

    it('merges with tighter min', function () {
        $a = PhpVersionConstraint::since(PhpVersion::PHP_7_1);
        $b = PhpVersionConstraint::since(PhpVersion::PHP_7_3);

        expect($a->merge($b)->min)->toBe(PhpVersion::PHP_7_3)
            ->and($b->merge($a)->min)->toBe(PhpVersion::PHP_7_3);
    });

    it('merges with tighter max', function () {
        $a = PhpVersionConstraint::until(PhpVersion::PHP_7_4);
        $b = PhpVersionConstraint::until(PhpVersion::PHP_7_2);

        expect($a->merge($b)->max)->toBe(PhpVersion::PHP_7_2);
    });

    it('merges with identity on equal values', function () {
        $first = PhpVersionConstraint::between(PhpVersion::PHP_5_6, PhpVersion::PHP_7_0);
        $second = PhpVersionConstraint::between(PhpVersion::PHP_5_6, PhpVersion::PHP_7_0);

        expect($first->merge($second))->toEqual($first)
            ->and($second->merge($first))->toEqual($first);
    });

    it('merges with identity on null values', function () {
        $any = PhpVersionConstraint::since(PhpVersion::PHP_5_6);
        $open = PhpVersionConstraint::open(); // no bounds

        expect($any->merge($open))->toEqual($any)
            ->and($open->merge($any))->toEqual($any);
    });

    it('converts to string', function (?PhpVersion $lower, ?PhpVersion $higher) {
        $constraint = PhpVersionConstraint::between($lower, $higher);

        expect($constraint)
            ->when(! is_null($lower), fn ($constraint) => $constraint->__toString()->toContain($lower->toVersionString()))
            ->when(! is_null($higher), fn ($constraint) => $constraint->__toString()->toContain($higher->toVersionString()));
    })->with('version sets');
});
