<?php

use App\Language\PhpVersion;
use App\Language\PhpVersionVector;

dataset('min', [
    // values, min
    [[1.0, 2.0, 3.0], 1.0],
    [[1.0, 1.0, 1.0], 1.0],
    [[0.0, 0.0, 0.0], 0.0],
    [[-1.0, -2.0, -3.0], -3.0],
]);

dataset('max', [
    // values, max
    [[1.0, 2.0, 3.0], 3.0],
    [[1.0, 1.0, 1.0], 1.0],
    [[0.0, 0.0, 0.0], 0.0],
    [[-1.0, -2.0, -3.0], -1.0],
]);

dataset('normalized', [
    // values before, pad, values after
    [[0.0, 1.0, 2.0, 3.0, 4.0], 0.0, [0.0, 0.25, 0.50, 0.75, 1.00]],
    [[2.0, 3.0, 4.0, 5.0, 6.0], 2.0, [0.0, 0.25, 0.50, 0.75, 1.00]],
    [[0.0, 0.0], 2.0, [0.0, 0.0]],

    // 1.0 normalizes to 1.0,
    [[1.0, 1.0, 1.0], 1.0, [1.0, 1.0, 1.0]],
    // 0.0 normalizes to 0.0,
    [[0.0, 0.0, 0.0], 0.0, [0.0, 0.0, 0.0]],
    // 0.50 normalizes to 0.50,
    [[0.5, 0.5, 0.5], 0.5, [0.5, 0.5, 0.5]],
    // -1.0 normalizes to 0.0,
    [[-1.0, -1.0, -1.0], -1.0, [0.0, 0.0, 0.0]],
    // and 2.0 normalizes to 1.0.
    [[2.0, 2.0, 2.0], 2.0, [1.0, 1.0, 1.0]],
]);

dataset('scaled', [
    // values before, scale, values after
    [[1.0, 2.0, 3.0], 3.0, [3.0, 6.0, 9.0]],
    [[1.0, 2.0, 3.0], 1.0, [1.0, 2.0, 3.0]],
]);

describe('math', function () {
    it('computes min', function (array $values, float $min) {
        $vector = PhpVersionVector::of(padVersionFloatsWith(INF, ...$values));

        expect($vector->min())->toBe($min);
    })->with('min');

    it('computes max', function (array $values, float $max) {
        $vector = PhpVersionVector::of(padVersionFloatsWith(-INF, ...$values));

        expect($vector->max())->toBe($max);
    })->with('max');

    it('scales', function (array $before, float $scale, array $after) {
        $vector = PhpVersionVector::of(padVersionFloats(...$before))->scale($scale);

        expect($vector)->toBeVersionVector(...$after);
    })->with('scaled');

    it('normalizes', function (array $before, float $pad, array $after) {
        $vector = PhpVersionVector::of(padVersionFloatsWith($pad, ...$before))->rescale();

        expect($vector)->toBeVersionVector(...$after);
    })->with('normalized');
});

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

    it('rejects when not enough values are passed', function (array $values) {
        PhpVersionVector::of($values);
    })->throws(InvalidArgumentException::class)
        ->with([
            [array_fill(0, PhpVersion::count() - 1, 0.0)],
            [array_fill(0, PhpVersion::count() + 1, 0.0)],
        ]);
});

describe('add', function () {
    it('adds values', function () {
        $first = PhpVersionVector::of(padVersionFloats(1.0, 2.0, 3.0));
        $second = PhpVersionVector::of(padVersionFloats(2.0, 4.0, 6.0));

        $sum = $first->add($second);

        expect($sum)->toBeVersionVector(3.0, 6.0, 9.0);
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

    it('requires PhpVersion instances as keys when getting values', function () {
        $vector = PhpVersionVector::zero();

        $vector[0];
    })->throws(InvalidArgumentException::class);

    it('requires PhpVersion instances as keys when setting values', function () {
        $vector = PhpVersionVector::zero();

        $vector[0] = 3.0;
    })->throws(InvalidArgumentException::class);

    it('does not allow deleting offsets', function () {
        $vector = PhpVersionVector::zero();

        unset($vector[PhpVersion::PHP_5_1]);
    })->throws(BadMethodCallException::class);

    it('indicates it has offsets for all known PHP versions', function () {
        $vector = PhpVersionVector::zero();

        foreach (PhpVersion::orderedCases() as $version) {
            expect(isset($vector[$version]))->toBe(true);
        }
    });
});
