<?php

namespace App\Catalogue;

use App\Language\PhpVersion;
use App\Language\PhpVersionConstraint;
use RuntimeException;
use UnexpectedValueException;

/**
 * Registration of version information for a certain identifier.
 */
abstract class IdentifierCatalogue
{
    /**
     * Registrations of minimum PHP versions for the identifiers.
     *
     * @var array<string, PhpVersion>
     */
    private static array $min = [];

    /**
     * Registrations of maximum PHP versions for registered identifiers.
     *
     * @var array<string, PhpVersion>
     */
    private static array $max = [];

    /**
     * @param  string  $name  Name of the identifier.
     */
    protected function __construct(
        public readonly string $name,
    ) {}

    /**
     * Process a row from the catalogue and load it.
     *
     * @param  string[]  $row
     */
    protected static function processFromCatalogue(array $row): void
    {
        if (count($row) < 2) {
            throw new UnexpectedValueException('A row in the constants database should have at least 2 columns; it has '.count($row).'.');
        }

        if (count($row) < 3) {
            $row[] = '';
        }

        [$name, $since, $until] = $row;

        $identifier = static::for($name);

        if (! empty($since)) {
            $identifier->since(PhpVersion::fromVersionString($since));
        }
        if (! empty($until)) {
            $identifier->until(PhpVersion::fromVersionString($until));
        }
    }

    final public static function loadFromCatalogue(string $catalogue): void
    {
        $fp = fopen(resource_path('catalogue/'.$catalogue.'.csv'), 'r');

        if ($fp === false) {
            throw new RuntimeException('Unable to open identifier catalogue file '.$catalogue.'.');
        }

        fgetcsv($fp);

        while (($row = fgetcsv($fp)) !== false) {
            /** @var string[] $row */
            static::processFromCatalogue($row);
        }
    }

    /**
     * Construct a new registration of version information for a certain identifier.
     */
    final public static function for(string $name): static
    {
        return new static($name);
    }

    /**
     * Register that the identifier was introduced in the given version.
     *
     * @return $this
     */
    final public function since(PhpVersion $min): static
    {
        $current = self::$min[$this->name] ?? PhpVersion::getNewestSupported();

        $next = PhpVersion::min($min, $current);
        self::$min[$this->name] = $next;

        return $this;
    }

    /**
     * Register that the identifier was available until and including the given version.
     *
     * N.B. If an identifier was removed in a certain version, the version supplied to this method should be the version
     * preceding the version in which the identifier was removed. For example: if an identifier was removed in version
     * 5.6, version 5.5 should be supplied to this function.
     *
     * @return $this
     */
    final public function until(PhpVersion $max): static
    {
        $current = self::$max[$this->name] ?? PhpVersion::getOldestSupported();

        $next = PhpVersion::max($max, $current);
        self::$max[$this->name] = $next;

        return $this;
    }

    /**
     * Get the {@link PhpVersionConstraint} for the given identifier.
     */
    public static function constraintFor(string $name): PhpVersionConstraint
    {
        $min = self::$min[$name] ?? null;
        $max = self::$max[$name] ?? null;

        return PhpVersionConstraint::between($min, $max);
    }
}
