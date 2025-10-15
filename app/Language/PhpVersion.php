<?php

namespace App\Language;

use InvalidArgumentException;

/**
 * A PHP version, with its PHP_VERSION_ID integer as scalar equivalent.
 *
 * @see PHP_VERSION_ID
 */
enum PhpVersion: int
{
    case PHP_5_0 = 50000;
    case PHP_5_1 = 50100;
    case PHP_5_2 = 50200;
    case PHP_5_3 = 50300;
    case PHP_5_4 = 50400;
    case PHP_5_5 = 50500;
    case PHP_5_6 = 50600;
    case PHP_7_0 = 70000;
    case PHP_7_1 = 70100;
    case PHP_7_2 = 70200;
    case PHP_7_3 = 70300;
    case PHP_7_4 = 70400;
    case PHP_8_0 = 80000;
    case PHP_8_1 = 80100;
    case PHP_8_2 = 80200;
    case PHP_8_3 = 80300;
    case PHP_8_4 = 80400;

    /**
     * @return int<0,max>
     */
    public static function count(): int
    {
        static $count;

        return $count ??= count(self::cases());
    }

    /**
     * @return list<self>
     */
    public static function orderedCases(): array
    {
        static $orderedCases = null;

        if ($orderedCases === null) {
            $orderedCases = self::cases();
            usort($orderedCases, fn (self $a, self $b): int => $a->value <=> $b->value);
        }

        return $orderedCases;
    }

    public function getOrderedKey(): int
    {
        static $orderedKeys = [];

        if (! isset($orderedKeys[$this->value])) {
            $orderedKeys[$this->value] = array_find_key(self::orderedCases(), fn (self $v): bool => $v === $this);
        }

        return $orderedKeys[$this->value];
    }

    public function next(): ?PhpVersion
    {
        $key = $this->getOrderedKey() + 1;

        return $key === self::count() ? null : self::orderedCases()[$key];

    }

    public function previous(): ?PhpVersion
    {
        $key = $this->getOrderedKey() - 1;

        return isset(self::orderedCases()[$key]) ? self::orderedCases()[$key] : null;
    }

    public static function min(?PhpVersion $version1, ?PhpVersion $version2): ?PhpVersion
    {
        if ($version1 === null) {
            return $version2;
        }

        if ($version2 === null) {
            return $version1;
        }

        return $version1->isOlderThan($version2) ? $version1 : $version2;
    }

    public static function max(?PhpVersion $version1, ?PhpVersion $version2): ?PhpVersion
    {
        if ($version1 === null) {
            return $version2;
        }

        if ($version2 === null) {
            return $version1;
        }

        return $version1->isNewerThan($version2) ? $version1 : $version2;
    }

    public static function getNewestSupported(): self
    {
        return self::PHP_8_4;
    }

    public static function getOldestSupported(): self
    {
        return self::PHP_5_0;
    }

    public function isNewerThan(PhpVersion $other): bool
    {
        return $this->value > $other->value;
    }

    public function isNewerThanOrEqualTo(PhpVersion $other): bool
    {

        return $this->value >= $other->value;
    }

    public function isOlderThan(PhpVersion $other): bool
    {
        return $this->value < $other->value;
    }

    public function isOlderThanOrEqualTo(PhpVersion $other): bool
    {
        return $this->value <= $other->value;
    }

    public function getMajor(): int
    {
        return (int) floor($this->value / 10_000);
    }

    public function getMinor(): int
    {
        return ($this->value % 10_000) / 100;
    }

    public function toVersionString(): string
    {
        return $this->getMajor().'.'.$this->getMinor();
    }

    /**
     * Retrieve a PHP version based on the version string.
     *
     * Warning: this method is slow.
     *
     * @param  string  $versionString  The version, formatted as <code><major>.<minor></code>.
     */
    public static function fromVersionString(string $versionString): PhpVersion
    {
        if (! preg_match('/^(\d)\.(\d)$/', $versionString, $matches)) {
            throw new InvalidArgumentException('Invalid version string: '.$versionString);
        }

        [, $major, $minor] = $matches;

        return self::tryFrom(intval($major) * 10_000 + intval($minor) * 100)
            ?? throw new InvalidArgumentException('Unknown version string: '.$versionString);
    }
}
