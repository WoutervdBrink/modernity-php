<?php

namespace App\Parser;

use Override;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\PhpVersion;

/**
 * Extension of {@link ParserFactory} that keeps a cache of instantiated parsers per PHP version.
 */
final class CachingParserFactory extends ParserFactory
{
    #[Override]
    public function createForVersion(PhpVersion $version): Parser
    {
        static $cache;

        return $cache[$version->id] ??= parent::createForVersion($version);
    }
}
