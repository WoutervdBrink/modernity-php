<?php

namespace App\Catalogue;

use App\Language\PhpVersion;
use App\Language\PhpVersionConstraint;

/**
 * Registration of version information for a certain constant.
 */
final class Constant
{
    /**
     * Registrations of minimum PHP versions for constants.
     *
     * @var array<string, PhpVersion>
     */
    private static array $min = [];

    /**
     * Registrations of maximum PHP versions for constants.
     *
     * @var array<string, PhpVersion>
     */
    private static array $max = [];

    /**
     * @param  string  $name  Name of the constant.
     */
    private function __construct(
        public readonly string $name,
    ) {}

    /**
     * Construct a new registration of version information for a certain constant.
     */
    public static function for(string $name): self
    {
        return new self($name);
    }

    /**
     * Register that the constant was introduced in the given version.
     *
     * @return $this
     */
    public function since(PhpVersion $min): self
    {
        $current = self::$min[$this->name] ?? PhpVersion::getNewestSupported();

        $next = PhpVersion::min($min, $current);
        self::$min[$this->name] = $next;

        return $this;
    }

    /**
     * Register that the constant was available until and including the given version.
     *
     * N.B. If a constant was removed in a certain version, the version supplied to this method should be the version
     * preceding the version in which the constant was removed. For example: if a constant was removed in version 5.6,
     * version 5.5 should be supplied to this function.
     *
     * @return $this
     */
    public function until(PhpVersion $max): self
    {
        $current = self::$max[$this->name] ?? PhpVersion::getOldestSupported();

        $next = PhpVersion::max($max, $current);
        self::$max[$this->name] = $next;

        return $this;
    }

    /**
     * Get the {@link PhpVersionConstraint} for the given constant.
     */
    public static function constraintFor(string $name): PhpVersionConstraint
    {
        $min = self::$min[$name] ?? null;
        $max = self::$max[$name] ?? null;

        return PhpVersionConstraint::between($min, $max);
    }
}
