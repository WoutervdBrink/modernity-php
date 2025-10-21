<?php

namespace App\Parser\Visitors;

use App\Language\PhpVersionConstraint;
use DOMDocument;
use DOMException;
use DOMNode;
use LogicException;
use Override;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class ASTToXMLConvertingVisitor extends NodeVisitorAbstract
{
    public private(set) DOMDocument $document;

    private DOMNode $cursor;

    /**
     * @throws DOMException
     */
    #[Override]
    public function beforeTraverse(array $nodes): null
    {
        $this->document = new DOMDocument;
        $this->document->formatOutput = true;

        $cursor = $this->document->createElement('ast');
        $this->document->appendChild($cursor);

        $this->cursor = $cursor;

        return null;
    }

    private static function convertAttributeValueToString(mixed $value): string
    {
        if ($value === null) {
            return 'null';
        }
        if (is_scalar($value)) {
            return (string) $value;
        }
        if (is_array($value)) {
            return '['.implode(', ', array_map(self::convertAttributeValueToString(...), $value)).']';
        }
        if (is_object($value) && method_exists($value, '__toString')) {
            return $value->__toString();
        }

        return '__not_a_string__';
    }

    /**
     * @throws DOMException
     */
    #[Override]
    public function enterNode(Node $node): null
    {
        $child = $this->document->createElement($node->getType());

        foreach ($node->getAttributes() as $key => $value) {
            if (preg_match('/(start|end)(Line|TokenPos|FilePos)/', $key)) {
                continue;
            }

            if ($key === 'constraint' && $value instanceof PhpVersionConstraint) {
                $child->setAttribute('constraintMin', $value->min?->toVersionString() ?? 'none');
                $child->setAttribute('constraintMax', $value->max?->toVersionString() ?? 'none');

                continue;
            }

            $child->setAttribute($key, self::convertAttributeValueToString($value));
        }

        $this->cursor->appendChild($child);
        $this->cursor = $child;

        return null;
    }

    #[Override]
    public function leaveNode(Node $node): null
    {
        if ($this->cursor->parentNode === null) {
            throw new LogicException('This should never happen!');
        }

        $this->cursor = $this->cursor->parentNode;

        return null;
    }
}
