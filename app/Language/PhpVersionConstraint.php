<?php

namespace App\Language;

use InvalidArgumentException;

/**
 * Some constraint on PHP versions.
 */
final readonly class PhpVersionConstraint
{
    private function __construct(
        /**
         * @var PhpVersion|null The minimum required version.
         *
         * If equal to <code>null</code>, there is no lower version constraint.
         */
        public ?PhpVersion $min = null,

        /**
         * @var PhpVersion|null The maximum required version.
         *
         * If equal to <code>null</code>, there is no upper version constraint.
         */
        public ?PhpVersion $max = null,
    ) {
        if (! is_null($max) && $min?->isNewerThan($max)) {
            throw new InvalidArgumentException('min must be <= max');
        }
    }

    /**
     * Create a version constraint without bounds.
     */
    public static function open(): self
    {
        static $empty;

        return $empty ??= new self;
    }

    /**
     * Create a version constraint bound by a lower version (inclusive).
     *
     * @param  PhpVersion|null  $version  Minimum required version.
     */
    public static function since(?PhpVersion $version): self
    {
        if ($version === null) {
            return self::open();
        }

        static $cache = [];

        return $cache[$version->value] ??= new self($version, null);
    }

    /**
     * Create a version constraint bound by an upper version (inclusive).
     *
     * @param  PhpVersion|null  $version  Maximum supported version.
     */
    public static function until(?PhpVersion $version): self
    {
        if ($version === null) {
            return self::open();
        }

        static $cache = [];

        return $cache[$version->value] ??= new self(null, $version);
    }

    /**
     * Create a version constraint bound by a lower and upper version (both inclusive).
     *
     * @param  PhpVersion|null  $min  Minimum required version.
     * @param  PhpVersion|null  $max  Maximum supported version.
     */
    public static function between(?PhpVersion $min, ?PhpVersion $max): self
    {
        return new self($min, $max);
    }

    /**
     * Combine this version constraint with the given version constraint, creating a new constraint that respects both.
     *
     * The resulting version constraint has minimum version <code>max(min)</code> and maximum version
     * <code>min(max)</code>.
     *
     * @return $this|self
     */
    public function merge(self $other): self
    {
        if ($this->min === $other->min && $this->max === $other->max) {
            return $this;
        }

        return new PhpVersionConstraint(
            PhpVersion::max($this->min, $other->min),
            PhpVersion::min($this->max, $other->max),
        );
    }

    public function __toString(): string
    {
        return sprintf('PhpVersionConstraint<min=%s, max=%s>', $this->min?->toVersionString() ?? '--', $this->max?->toVersionString() ?? '--');
    }
}
