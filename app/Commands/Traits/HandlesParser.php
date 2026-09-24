<?php

namespace App\Commands\Traits;

use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\PhpVersion;

trait HandlesParser
{
    private function getRequestedParser(ParserFactory $factory): Parser|false
    {
        $parser = $this->option('parser');

        if (! is_string($parser)) {
            $this->error('Parser must be a string');

            return false;
        }

        return $factory->createForVersion($parser === 'latest' ? PhpVersion::getNewestSupported() : PhpVersion::fromString($parser));
    }
}
