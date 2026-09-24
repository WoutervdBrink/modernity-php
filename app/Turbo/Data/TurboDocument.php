<?php

namespace App\Turbo\Data;

use Spatie\LaravelData\Data;

final class TurboDocument extends Data
{
    /**
     * @param  int  $formatVersion  Version of the Turbo format specification.
     * @param  string  $language  Identifier of the programming language.
     * @param  array<int, string>  $versions  Ordered list of considered language versions. The order of entries in
     *                                        <code>$versions</code> defines chronological/version ordering. Version
     *                                        identifiers themselves are opaque strings.
     * @param  array<int, string>  $nodeTypes  Ordered list of considered node types.
     * @param  array<int, TurboNode>  $roots  Ordered list of AST root nodes.
     */
    public function __construct(
        public int $formatVersion,
        public string $language,
        public array $versions,
        public array $nodeTypes,
        public array $roots,
    ) {}
}
