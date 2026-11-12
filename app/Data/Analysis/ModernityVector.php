<?php

namespace App\Data\Analysis;

use Ds\Vector;
use InvalidArgumentException;

final class ModernityVector
{
    /**
     * @var Vector<float>
     */
    private Vector $values;

    /**
     * @param  Vector<float>|array<float>|null  $values
     */
    public function __construct(
        public VersionAxis $versions,
        Vector|array|null $values = null,
    ) {
        if (is_array($values)) {
            $values = new Vector($values);
        }

        if (
            $values instanceof Vector &&
            $values->count() === $this->versions->count()
        ) {
            $this->values = $values;
        } elseif ($values === null) {
            $this->values = new Vector;
            $this->values->push(...array_fill(0, $this->versions->count(), 0.0));
        } else {
            throw new InvalidArgumentException('Invalid vector values');
        }
    }

    public function add(self $other): self
    {
        if (! $other->versions->equals($this->versions)) {
            throw new InvalidArgumentException('Vectors have different versions and cannot be added together.');
        }

        $new = clone $this;

        for ($i = 0; $i < $this->versions->count(); $i++) {
            $new->values[$i] = $new->at($i) + $other->at($i);
        }

        return $new;
    }

    public function at(int $index): float
    {
        if (isset($this->values[$index])) {
            return (float) $this->values[$index];
        }

        throw new InvalidArgumentException('Index out of range');
    }

    public function min(): float
    {
        $values = $this->values->toArray();

        assert($values !== []);

        return min($values);
    }

    public function max(): float
    {
        $values = $this->values->toArray();

        assert($values !== []);

        return max($values);
    }

    public function scale(int|float $factor): self
    {
        $new = new self($this->versions);

        $new->values = $this->values->map(fn (float $value): float => $value * (float) $factor);

        return $new;
    }
}
