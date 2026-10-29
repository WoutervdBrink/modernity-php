<?php

namespace App\Catalogue;

/**
 * Registration of version information for a certain constant.
 */
final class Constant extends IdentifierWithParentClassCatalogue
{
    public static function for(string $name): static
    {
        return new self(self::normalizeName($name));
    }

    public static function forClass(string $name, string $className): static
    {
        return new self($name, $className);
    }
}
