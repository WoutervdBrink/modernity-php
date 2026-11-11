<?php

namespace App\Data\SourceFile;

use App\Models\Enums\ContentEncoding;
use Spatie\LaravelData\Data;

final class SourceFileContentsData extends Data
{
    public function __construct(
        public ContentEncoding $content_encoding,
        public string $contents,
    ) {}
}
