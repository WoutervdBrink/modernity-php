<?php

namespace App\Catalogue;

/**
 * Registration of version information for a certain class.
 *
 * <code>Class</code> is a keyword, which is why this class is called <code>Clazz</code>.
 */
final class Clazz extends IdentifierCatalogue
{
    protected static function normalizeName(string $name): string
    {
        return mb_strtolower($name);
    }

    final public static function for(string $name): static
    {
        return new self(self::normalizeName($name));
    }
}
