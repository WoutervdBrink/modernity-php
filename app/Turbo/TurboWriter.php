<?php

namespace App\Turbo;

use App\Language\PhpVersion;
use App\Language\PhpVersionConstraint;
use App\Turbo\Data\TurboDocument;
use App\Turbo\Data\TurboNode;
use App\Turbo\Data\TurboRange;
use Ds\Map;
use Override;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

final class TurboWriter extends NodeVisitorAbstract
{
    private TurboDocument $doc;

    private TurboDocument|TurboNode|null $parent = null;

    private TurboDocument|TurboNode $cursor;

    /**
     * @var Map<string, int>
     */
    private Map $typeMap;

    #[Override]
    public function beforeTraverse(array $nodes): ?array
    {
        $this->doc = new TurboDocument(
            1,
            'php',
            array_map(fn (PhpVersion $v): string => $v->toVersionString(), PhpVersion::orderedCases()),
            [],
            []
        );

        $this->cursor = $this->doc;

        $this->typeMap = new Map;

        return null;
    }

    private function getTypeIndex(string $nodeType): int
    {
        if (! $this->typeMap->hasKey($nodeType)) {
            $this->doc->nodeTypes[] = $nodeType;
            $this->typeMap->put($nodeType, count($this->doc->nodeTypes) - 1);
        }

        return $this->typeMap->get($nodeType);
    }

    #[Override]
    public function enterNode(Node $node): null
    {
        $ranges = [];
        $constraint = $node->getAttribute('constraint');

        if ($constraint instanceof PhpVersionConstraint) {
            $ranges[] = new TurboRange(
                $constraint->min?->getOrderedKey() ?? null,
                $constraint->max?->getOrderedKey() ?? null,
            );
        }

        $cursor = new TurboNode($this->getTypeIndex($node->getType()), $ranges, []);

        if ($this->cursor instanceof TurboNode) {
            $this->cursor->children[] = $cursor;
        } else {
            $this->cursor->roots[] = $cursor;
        }

        $this->parent = $this->cursor;
        $this->cursor = $cursor;

        return null;
    }

    #[Override]
    public function leaveNode(Node $node): null
    {
        $this->cursor = $this->parent;

        return null;
    }

    public function write(string $path): void
    {
        file_put_contents($path, $this->doc->toJson());
    }
}
