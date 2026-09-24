<?php

namespace App\Turbo;

use App\Turbo\Data\TurboDocument;

final class TurboReader
{
    public function read(string $path): TurboDocument
    {
        return TurboDocument::from(file_get_contents($path));
    }
}
