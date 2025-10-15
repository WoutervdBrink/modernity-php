<?php

namespace App\Language;

use ArrayAccess;
use BadMethodCallException;
use Countable;
use Ds\Vector;
use InvalidArgumentException;
use Iterator;
use Override;

/**
 * @implements ArrayAccess<PhpVersion, float>
 * @implements Iterator<PhpVersion, float>
 */
final class PhpVersionVector implements ArrayAccess, Countable, Iterator
{
    /**
     * @var Vector<float>
     */
    private Vector $values;

    /**
     * @var ?PhpVersion Internal state for {@link Iterator} methods.
     */
    private ?PhpVersion $key = null;

    /**
     * @param  Vector<float>|null  $values
     */
    private function __construct(?Vector $values = null)
    {
        $this->values = $values ?? self::emptyVector();
    }

    /**
     * @param  list<float>  $values
     */
    public static function of(array $values): self
    {
        if (count($values) !== PhpVersion::count()) {
            throw new InvalidArgumentException(
                'Amount of values passed to PhpVersionVector::of() ('.count($values).
                ') must be equal to the amount of defined PHP versions ('.PhpVersion::count().').'
            );
        }

        return new self(new Vector($values));
    }

    /**
     * @return Vector<float>
     */
    private static function emptyVector(): Vector
    {
        static $empty;

        if (! empty($empty)) {
            return $empty;
        }

        $empty = new Vector;
        $empty->push(...array_fill(0, PhpVersion::count(), 0.0));

        return $empty;
    }

    public static function zero(): self
    {
        return new self;
    }

    public function add(self $other): self
    {
        /** @var Vector<float> $newVector */
        $newVector = new Vector;

        for ($i = 0; $i < PhpVersion::count(); $i++) {
            $newVector[$i] = (float) ($this->values[$i] + $other->values[$i]);
        }

        return new self($newVector);
    }

    public function min(): float
    {
        return $this->values->reduce(fn (float $carry, mixed $value): mixed => min($carry, $value), 0.0);
    }

    public function normalize(): self
    {
        return $this->scale($this->max());
    }

    public function scale(int|float $factor): self
    {
        return new self($this->values->map(fn (float $value): float => $value * (float) $factor));
    }

    public function max(): float
    {
        return $this->values->reduce(fn (float $carry, mixed $value): mixed => max($carry, $value), 0.0);
    }

    #[Override]
    public function offsetExists(mixed $offset): bool
    {
        return $this->values->offsetExists(self::getKey($offset));
    }

    private static function getKey(mixed $offset): int
    {
        if (! $offset instanceof PhpVersion) {
            throw new InvalidArgumentException('Array keys of PhpVersionVector must be an instance of PhpVersion.');
        }

        return $offset->getOrderedKey();
    }

    #[Override]
    public function offsetGet(mixed $offset): ?float
    {
        return $this->values->offsetGet(self::getKey($offset));
    }

    #[Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->values->offsetSet(self::getKey($offset), $value);
    }

    #[Override]
    public function offsetUnset(mixed $offset): void
    {
        throw new BadMethodCallException('Calling offsetUnset() on a PhpVersionVector is not supported.');
    }

    #[Override]
    public function current(): ?float
    {
        return $this->key ? $this[$this->key] : null;
    }

    #[Override]
    public function next(): void
    {
        $this->key = $this->key?->next();
    }

    #[Override]
    public function key(): ?PhpVersion
    {
        return $this->key;
    }

    #[Override]
    public function valid(): bool
    {
        return ! is_null($this->key);
    }

    #[Override]
    public function rewind(): void
    {
        $this->key = array_first(PhpVersion::orderedCases());
    }

    #[Override]
    public function count(): int
    {
        return PhpVersion::count();
    }
}
