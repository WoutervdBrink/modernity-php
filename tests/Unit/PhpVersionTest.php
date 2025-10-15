<?php

use App\Language\PhpVersion;

dataset('ordered doubles', [
    // A > B
    [PhpVersion::PHP_5_1, PhpVersion::PHP_5_0],
    [PhpVersion::PHP_8_0, PhpVersion::PHP_7_4],
]);

dataset('ordered triples', [
    // A > B > C
    [PhpVersion::PHP_5_2, PhpVersion::PHP_5_1, PhpVersion::PHP_5_0],
    [PhpVersion::PHP_8_0, PhpVersion::PHP_7_4, PhpVersion::PHP_5_4],
]);

dataset('version components', [
    // version, major, minor
    [PhpVersion::PHP_5_1, 5, 1],
    [PhpVersion::PHP_8_0, 8, 0],
]);

dataset('invalid versions', [
    '',
    'foo.bar',
    'foo',
    '5.6.3',
    '4.0',
]);

describe('formatting', function () {
    it('returns major and minor components', function (PhpVersion $version, int $major, int $minor) {
        expect($version->getMajor())->toBe($major)
            ->and($version->getMinor())->toBe($minor);
    })->with('version components');

    it('returns the version string', function (PhpVersion $version, int $major, int $minor) {
        expect($version->toVersionString())->toBe($major.'.'.$minor);
    })->with('version components');

    it('retrieves based on version string', function (PhpVersion $version, int $major, int $minor) {
        expect(PhpVersion::fromVersionString($major.'.'.$minor))->toBe($version);
    })->with('version components');

    it('rejects an invalid version string', function () {
        PhpVersion::fromVersionString('9.a');
    })->with('invalid versions')->throws(InvalidArgumentException::class);
});

describe('ordered cases', function () {
    it('returns an array of PhpVersion instances', function () {
        $actual = PhpVersion::orderedCases();

        expect($actual)->toBeArray()
            ->and($actual)->each->toBeInstanceOf(PhpVersion::class);
    });

    it('contains all and only the enum cases', function () {
        $expected = PhpVersion::cases();
        $actual = PhpVersion::orderedCases();

        expect($actual)->toHaveCount(count($expected));

        $missing = array_udiff($expected, $actual, fn (PhpVersion $a, PhpVersion $b) => $a->value <=> $b->value);
        $extra = array_udiff($actual, $expected, fn (PhpVersion $a, PhpVersion $b) => $a->value <=> $b->value);

        expect($missing)->toBeEmpty()
            ->and($extra)->toBeEmpty();
    });

    it('is ordered by version ascending', function () {
        $actual = PhpVersion::orderedCases();

        for ($i = 1; $i < count($actual); $i++) {
            expect($actual[$i - 1]->value)->toBeLessThanOrEqual($actual[$i]->value);
        }
    });
});

describe('previous', function () {
    it('returns null for the oldest version', function () {
        $oldest = array_first(PhpVersion::orderedCases());

        expect($oldest->previous())->toBeNull();
    });

    it('returns the newest older version', function () {
        for ($i = 1; $i < PhpVersion::count(); $i++) {
            $current = PhpVersion::orderedCases()[$i];
            $older = PhpVersion::orderedCases()[$i - 1];

            expect($current->previous())->toBe($older);
        }
    });
});

describe('next', function () {
    it('returns the oldest newer version', function () {
        for ($i = 0; $i < PhpVersion::count() - 1; $i++) {
            $current = PhpVersion::orderedCases()[$i];
            $newer = PhpVersion::orderedCases()[$i + 1];

            expect($current->next())->toBe($newer);
        }
    });

    it('returns null for the newest version', function () {
        $oldest = array_last(PhpVersion::orderedCases());

        expect($oldest->next())->toBeNull();
    });
});

describe('version ordering', function () {
    it('compares versions consistently (reflexivity)', function () {
        foreach (PhpVersion::cases() as $version) {
            expect($version->isOlderThan($version))->toBeFalse()
                ->and($version->isNewerThan($version))->toBeFalse
                ->and($version->isOlderThanOrEqualTo($version))->toBeTrue()
                ->and($version->isNewerThanOrEqualTo($version))->toBeTrue();
        }
    });

    it('compares versions consistently (symmetry)', function (PhpVersion $newer, PhpVersion $older) {
        expect($newer->isNewerThan($older))->toBeTrue()
            ->and($newer->isOlderThan($older))->toBeFalse()
            ->and($older->isOlderThan($newer))->toBeTrue()
            ->and($older->isNewerThan($newer))->toBeFalse();
    })->with('ordered doubles');

    it('compares versions consistently (transitiveness)', function (PhpVersion $new, PhpVersion $mid, PhpVersion $old) {
        expect($new->isNewerThan($mid))->toBeTrue()
            ->and($mid->isNewerThan($old))->toBeTrue()
            ->and($new->isNewerThan($old))->toBeTrue()
            ->and($old->isOlderThan($mid))->toBeTrue()
            ->and($mid->isOlderThan($new))->toBeTrue()
            ->and($old->isOlderThan($new))->toBeTrue();
    })->with('ordered triples');
});

describe('count', function () {
    it('returns the amount of cases', function () {
        expect(PhpVersion::count())->toBe(count(PhpVersion::cases()));
    });

    it('returns the same value on repeated calls (idempotence)', function () {
        expect(PhpVersion::count())->toBe(PhpVersion::count());
    });
});

describe('ordered keys', function () {
    it('keys equal the index in orderedCases()', function () {
        foreach (PhpVersion::orderedCases() as $i => $case) {
            expect($case->getOrderedKey())->toBe($i);
        }
    });

    it('returns the same key on repeated calls (idempotence)', function () {
        foreach (PhpVersion::cases() as $case) {
            $k1 = $case->getOrderedKey();
            $k2 = $case->getOrderedKey();

            expect($k1)->toBe($k2);
        }
    });

    it('is independent of enumeration order (permutation invariance)', function () {
        $shuffled = $baseline = PhpVersion::cases();
        shuffle($shuffled);

        $shuffled = array_map(fn (PhpVersion $c): int => $c->getOrderedKey(), $shuffled);
        $baseline = array_map(fn (PhpVersion $c): int => $c->getOrderedKey(), $baseline);

        sort($baseline);
        sort($shuffled);

        expect($shuffled)->toBe($baseline);
    });

    it('assigns a distinct key for each version', function () {
        $keys = [];
        foreach (PhpVersion::cases() as $version) {
            $key = $version->getOrderedKey();
            expect($keys)->not->toHaveKey($key);
            $keys[$key] = true;
        }
    });

    it('bounds keys between 0 and the amount of versions (exclusive)', function () {
        $keys = array_map(fn (PhpVersion $version) => $version->getOrderedKey(), PhpVersion::cases());
        sort($keys);

        expect($keys)->toEqual(range(0, count($keys) - 1));
    });
});

describe('oldest and newest versions', function () {
    it('returns the oldest version as an enum member', function () {
        $oldest = PhpVersion::getOldestSupported();
        expect($oldest)
            ->not->toBeNull()
            ->toBeInstanceOf(PhpVersion::class);
    });

    it('returns the newest version as an enum member', function () {
        $newest = PhpVersion::getNewestSupported();
        expect($newest)
            ->not->toBeNull()
            ->toBeInstanceOf(PhpVersion::class);
    });

    it('orders oldest before or equal to newest', function () {
        $oldest = PhpVersion::getOldestSupported();
        $newest = PhpVersion::getNewestSupported();

        expect($oldest->isOlderThanOrEqualTo($newest))->toBeTrue()
            ->and($newest->isNewerThanOrEqualTo($oldest));
    });

    it('bounds all known versions between oldest and newest', function () {
        $oldest = PhpVersion::getOldestSupported();
        $newest = PhpVersion::getNewestSupported();

        foreach (PhpVersion::cases() as $version) {
            expect($version->isNewerThanOrEqualTo($oldest))->toBeTrue()
                ->and($version->isOlderThanOrEqualTo($newest))->toBeTrue();
        }
    });
});

describe('minimum and maximum', function () {
    it('determines minimum versions when both are null', function () {
        expect(PhpVersion::min(null, null))->toBeNull();
    });

    it('determines minimum versions when one is null', function () {
        expect(PhpVersion::min(null, PhpVersion::PHP_5_4))->toBe(PhpVersion::PHP_5_4)
            ->and(PhpVersion::min(PhpVersion::PHP_5_4, null))->toBe(PhpVersion::PHP_5_4);
    });

    it('determines minimum versions', function (PhpVersion $newer, PhpVersion $older) {
        expect(PhpVersion::min($newer, $older))->toBe($older)
            ->and(PhpVersion::min($older, $newer))->toBe($older);
    })->with('ordered doubles');

    it('determines maximum versions when both are null', function () {
        expect(PhpVersion::max(null, null))->toBeNull();
    });

    it('determines maximum versions when one is null', function () {
        expect(PhpVersion::max(null, PhpVersion::PHP_5_4))->toBe(PhpVersion::PHP_5_4)
            ->and(PhpVersion::max(PhpVersion::PHP_5_4, null))->toBe(PhpVersion::PHP_5_4);
    });

    it('determines maximum versions', function (PhpVersion $newer, PhpVersion $older) {
        expect(PhpVersion::max($newer, $older))->toBe($newer)
            ->and(PhpVersion::max($older, $newer))->toBe($newer);
    })->with('ordered doubles');
});
