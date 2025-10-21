<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use App\Language\PhpVersion;
use App\Language\PhpVersionVector;

pest()->extend(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toHaveSameValues', function (array $expected) {
    $a = is_array($this->value) ? $this->value : [];
    $b = $expected;

    sort($a);
    sort($b);

    return expect($this->value)->toBeArray()
        ->and($a)->toEqual($b);
});

expect()->extend('toBeVersionVector', function (float ...$values) {
    expect($this->value)->toBeInstanceOf(PhpVersionVector::class);

    $versions = PhpVersion::orderedCases();

    foreach ($values as $key => $value) {
        expect($this->value[$versions[$key]])->toBe($value);
    }
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * Pad a given list of floats with zeroes so it is accepted by {@link PhpVersionVector::of}.
 *
 * The {@link PhpVersionVector::of} method only accepts arrays with entries for every available PHP
 * version. Not only would that be quite cumbersome for every test on version vectors; it would also result in
 * regressions whenever a new version is added to the {@link PhpVersion} enumeration.
 *
 * Thus, this function takes a generally incomplete list of float values, and pads zeroes to the end until its length is
 * equal to the amount of {@link PhpVersion} cases.
 *
 * @return float[]
 */
function padVersionFloats(float ...$values): array
{
    $ordered = PhpVersion::orderedCases();

    $out = array_fill(0, PhpVersion::count(), 0.0);

    foreach ($ordered as $key => $version) {
        if (isset($values[$key])) {
            $out[$key] = $values[$key];
        }
    }

    return $out;
}

/**
 * Pad a given list of floats with a value so it is accepted by {@link PhpVersionVector::of}.
 *
 * The {@link PhpVersionVector::of} method only accepts arrays with entries for every available PHP
 * version. Not only would that be quite cumbersome for every test on version vectors; it would also result in
 * regressions whenever a new version is added to the {@link PhpVersion} enumeration.
 *
 * Thus, this function takes a generally incomplete list of float values, and pads the value of <code>$with</code> to
 * the end until its length is equal to the amount of {@link PhpVersion} cases.
 *
 * @return float[]
 */
function padVersionFloatsWith(float $with, float ...$values): array
{
    $ordered = PhpVersion::orderedCases();

    $out = array_fill(0, PhpVersion::count(), $with);

    foreach ($ordered as $key => $version) {
        if (isset($values[$key])) {
            $out[$key] = $values[$key];
        }
    }

    return $out;
}
