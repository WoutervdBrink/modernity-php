<?php

namespace App\Services\Analysis\Php;

use App\Data\Analysis\Turbo\TurboDocument;
use App\Data\Analysis\Turbo\TurboNode;
use App\Data\Analysis\Turbo\TurboRange;
use App\Services\Analysis\Php\Language\PhpVersionConstraint;
use Ds\Map;
use LogicException;
use Override;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

final class TurboWriter extends NodeVisitorAbstract
{
    private TurboDocument $document;

    private TurboDocument|TurboNode $cursor;

    /**
     * @var list<TurboDocument|TurboNode>
     */
    private array $parents = [];

    /**
     * @var Map<string, int>
     */
    private Map $typeMap;

    #[Override]
    public function beforeTraverse(array $nodes): ?array
    {
        $this->document = new TurboDocument;

        $this->cursor = $this->document;
        $this->parents = [];
        $this->typeMap = new Map;

        return null;
    }

    #[Override]
    public function enterNode(Node $node): null
    {
        $ranges = [];

        $constraint = $node->getAttribute('constraint');

        if (
            $constraint instanceof PhpVersionConstraint &&
            ($constraint->min !== null || $constraint->max !== null)
        ) {
            $ranges[] = new TurboRange(
                $constraint->min?->getOrderedKey() ?? null,
                $constraint->max?->getOrderedKey() ?? null,
            );
        }

        $newNode = new TurboNode($this->getTypeIndex($node->getType()), $ranges, []);

        if ($this->cursor instanceof TurboNode) {
            $this->cursor->children[] = $newNode;
        } else {
            $this->cursor->roots[] = $newNode;
        }

        $this->parents[] = $this->cursor;
        $this->cursor = $newNode;

        return null;
    }

    private function getTypeIndex(string $nodeType): int
    {
        if (! $this->typeMap->hasKey($nodeType)) {
            $this->document->nodeTypes[] = $nodeType;
            $this->typeMap->put($nodeType, count($this->document->nodeTypes) - 1);
        }

        return $this->typeMap->get($nodeType);
    }

    #[Override]
    public function leaveNode(Node $node): null
    {
        $parent = array_pop($this->parents);

        if ($parent === null) {
            throw new LogicException('TurboWriter parent stack underflow');
        }

        $this->cursor = $parent;

        return null;
    }

    public function document(): TurboDocument
    {
        return $this->document;
    }
}
