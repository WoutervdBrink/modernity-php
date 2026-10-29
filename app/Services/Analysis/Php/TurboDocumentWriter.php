<?php

namespace App\Services\Analysis\Php;

use App\Data\Analysis\Turbo\TurboDocument;
use PhpParser\Node;
use PhpParser\NodeTraverser;

final readonly class TurboDocumentWriter
{
    private TurboWriter $writer;

    private NodeTraverser $traverser;

    public function __construct()
    {
        $this->traverser = new NodeTraverser;
        $this->writer = new TurboWriter;

        $this->traverser->addVisitor($this->writer);
    }

    /**
     * @param  Node[]  $ast
     */
    public function write(array $ast): TurboDocument
    {
        $this->traverser->traverse($ast);

        return $this->writer->document();
    }
}
