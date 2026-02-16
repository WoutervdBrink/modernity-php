<?php

namespace App\Catalogue;

use App\Language\PhpVersion;
use App\Language\PhpVersionConstraint;
use Override;
use UnexpectedValueException;

abstract class IdentifierWithParentClassCatalogue extends IdentifierCatalogue
{
    /**
     * @param  string  $name  Name of the identifier.
     * @param  ?class-string  $className  Name of the class, if the identifier is a static method or property of this class.
     */
    protected function __construct(string $name, ?string $className = null)
    {
        parent::__construct(self::key($name, $className));
    }

    final protected static function key(string $name, ?string $className): string
    {
        if (! empty($className)) {
            return $className.'::'.$name;
        }

        return $name;
    }

    /**
     * Process a row from the catalogue and load it.
     *
     * @param  string[]  $row
     */
    #[Override]
    protected static function processFromCatalogue(array $row): void
    {
        if (count($row) < 3) {
            throw new UnexpectedValueException('A row in the constants database should have at least 3 columns; it has '.count($row).'.');
        }

        if (count($row) < 4) {
            $row[] = '';
        }

        [$name, $class, $since, $until] = $row;

        if ($class === '') {
            $func = self::for($name);
        } else {
            /** @var class-string $class */
            $func = self::forClass($name, $class);
        }

        if (! empty($since)) {
            $func->since(PhpVersion::fromVersionString($since));
        }
        if (! empty($until)) {
            $func->until(PhpVersion::fromVersionString($until));
        }
    }

    /**
     * Construct a new registration of version information for a certain static identifier.
     *
     * @param  string  $name  Name of the identifier.
     * @param  class-string  $className  Name of the class.
     *
     * @see static::for() to register plain identifiers.
     *
     * @example <code>::forClass('connect', 'MySQL')</code>: Say something about the <code>MySQL::connect</code> identifier.
     */
    public static function forClass(string $name, string $className): static
    {
        return new static($name, $className);
    }

    /**
     * Get the {@link PhpVersionConstraint} for the given identifier and parent class name.
     */
    public static function constraintForWithClass(string $name, string $className): PhpVersionConstraint
    {
        return static::constraintFor(self::key($name, $className));
    }
}
