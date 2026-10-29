<?php

namespace App\Catalogue;

/**
 * Registration of version information for a certain constant.
 */
final class Attribute extends IdentifierCatalogue
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
