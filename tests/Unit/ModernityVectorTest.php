<?php

use App\Data\Analysis\ModernityVector;
use App\Data\Analysis\VersionAxis;

function makeVector(float ...$values): ModernityVector
{
    return new ModernityVector(new VersionAxis(array_map('strval', array_keys($values))), array_values($values));
}

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

dataset('scaled', [
    // values before, scale, values after
    [[1.0, 2.0, 3.0], 3.0, [3.0, 6.0, 9.0]],
    [[1.0, 2.0, 3.0], 1.0, [1.0, 2.0, 3.0]],
]);

describe('math', function (): void {
    it('computes min', function (array $values, float $min): void {
        $vector = makeVector(...$values);

        expect($vector->min())->toBe($min);
    })->with('min');

    it('computes max', function (array $values, float $max): void {
        $vector = makeVector(...$values);

        expect($vector->max())->toBe($max);
    })->with('max');

    it('scales', function (array $before, float $scale, array $after): void {
        $vector = makeVector(...$before)->scale($scale);

        expect($vector)->toBeVersionVector(...$after);
    })->with('scaled');
});

describe('add', function (): void {
    it('adds values', function (): void {
        $first = makeVector(1.0, 2.0, 3.0);
        $second = makeVector(2.0, 4.0, 6.0);

        $sum = $first->add($second);

        expect($sum)->toBeVersionVector(3.0, 6.0, 9.0);
    });
});
