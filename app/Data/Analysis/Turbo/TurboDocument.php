<?php

namespace App\Data\Analysis\Turbo;

use App\Language\PhpVersion;
use Spatie\LaravelData\Data;

final class TurboDocument extends Data
{
    /**
     * @var int Version of the Turbo format specification.
     */
    public int $formatVersion;

    /**
     * @var string Identifier of the programming language.
     */
    public string $language;

    /**
     * @var array<int, string> Ordered list of considered language versions. The order of entries in
     *                         <code>$versions</code> defines chronological/version ordering. Version
     *                         identifiers themselves are opaque strings.
     */
    public array $versions;

    /**
     * @param  array<int, string>  $nodeTypes  Ordered list of considered node types.
     * @param  array<int, TurboNode>  $roots  Ordered list of AST root nodes.
     */
    public function __construct(
        public array $nodeTypes = [],
        public array $roots = [],
    ) {
        $this->formatVersion = 1;
        $this->language = 'php';
        $this->versions = array_map(fn (PhpVersion $v): string => $v->toVersionString(), PhpVersion::orderedCases());
    }

    public function toJson($options = 0): string
    {
        return parent::toJson($options | JSON_THROW_ON_ERROR);
    }
}
