<?php

namespace App\Catalogue;

use App\Inspectors\Inspector;
use App\Language\PhpVersion;
use App\Language\PhpVersionConstraint;
use Closure;
use InvalidArgumentException;
use PhpParser\Node;
use ReflectionException;
use ReflectionFunction;
use ReflectionNamedType;
use RuntimeException;

/**
 * @template T of Node
 */
final class Feature
{
    /** @var array<class-string, list<Closure(Node): PhpVersionConstraint>> */
    private static array $rules = [];

    public function __construct(
        /** @var class-string<T> */
        public readonly string $nodeClass,
    ) {
        //
    }

    /**
     * @template U of Node
     *
     * @param  class-string<U>  $nodeClass
     * @return self<U>
     */
    public static function for(string $nodeClass): self
    {
        self::$rules[$nodeClass] ??= [];

        return new Feature($nodeClass);
    }

    /**
     * @psalm-suppress UnusedReturnValue, UnusedMethod
     *
     * @return $this
     */
    public function since(PhpVersion $min): self
    {
        return self::sinceWhen(fn (Node $_): PhpVersion => $min);
    }

    /**
     * @psalm-suppress UnusedReturnValue, UnusedMethod
     *
     * @param  callable(T): ?PhpVersion  $rule
     * @return $this
     */
    public function sinceWhen(callable $rule): self
    {
        return $this->rule(static fn (Node $n): PhpVersionConstraint => PhpVersionConstraint::since($rule($n)));
    }

    /**
     * @psalm-suppress UnusedReturnValue, UnusedMethod
     *
     * @param  callable(T): PhpVersionConstraint  $rule
     * @return $this
     */
    public function rule(callable $rule): self
    {
        $wrapped = $this->wrapRule($rule);
        self::$rules[$this->nodeClass][] = $wrapped;

        return $this;
    }

    /**
     * @psalm-suppress UnusedReturnValue, UnusedMethod
     *
     * @template I of Inspector
     *
     * @param  class-string<I>  $inspectorClass
     * @return $this
     */
    public function inspector(string $inspectorClass): self
    {
        return $this->rule($inspectorClass::inspect(...));
    }

    /**
     * @psalm-suppress UnusedReturnValue, UnusedMethod
     *
     * @return $this
     */
    public function until(PhpVersion $max): self
    {
        return self::untilWhen(fn (Node $_): PhpVersion => $max);
    }

    /**
     * @psalm-suppress UnusedReturnValue, UnusedMethod
     *
     * @param  callable(T): ?PhpVersion  $rule
     * @return $this
     */
    public function untilWhen(callable $rule): self
    {
        return $this->rule(static fn (Node $n): PhpVersionConstraint => PhpVersionConstraint::until($rule($n)));
    }

    /**
     * @psalm-suppress UnusedReturnValue, UnusedMethod
     *
     * @return $this
     */
    public function between(PhpVersion $min, PhpVersion $max): self
    {
        return self::rule(fn (Node $_): PhpVersionConstraint => PhpVersionConstraint::between($min, $max));
    }

    /**
     * @param  callable(T): PhpVersionConstraint  $rule
     * @return Closure(Node): PhpVersionConstraint
     */
    private function wrapRule(callable $rule): Closure
    {
        /** @var Closure(T): PhpVersionConstraint $closure */
        /** @noinspection PhpClosureCanBeConvertedToFirstClassCallableInspection */
        $closure = Closure::fromCallable($rule);

        try {
            $rf = new ReflectionFunction($rule(...));
        } catch (ReflectionException) {
            throw new RuntimeException('This should never happen.');
        }

        if ($rf->getNumberOfParameters() !== 1) {
            throw new InvalidArgumentException('Callback must take exactly one parameter.');
        }

        $param = $rf->getParameters()[0]->getType();

        if (! $param instanceof ReflectionNamedType || $param->isBuiltin()) {
            throw new InvalidArgumentException('Callback parameter must be a class type.');
        }

        $expectedParam = $param->getName();
        if (! is_a($this->nodeClass, $expectedParam, true)) {
            throw new InvalidArgumentException(sprintf('Callback parameter %s must accept %s', $expectedParam, $this->nodeClass));
        }

        $ret = $rf->getReturnType();
        if (! $ret instanceof ReflectionNamedType || $ret->getName() !== PhpVersionConstraint::class) {
            throw new InvalidArgumentException('Callback must return a PhpVersionConstraint');
        }

        return static function (Node $n) use ($closure): PhpVersionConstraint {
            return $closure($n);
        };
    }

    public static function constraintFor(Node $node): PhpVersionConstraint
    {
        $acc = PhpVersionConstraint::open();

        foreach (self::walkClasses($node) as $class) {
            foreach (self::$rules[$class] ?? [] as $rule) {
                $acc = $acc->merge($rule($node));
            }
        }

        return $acc;
    }

    /**
     * @param  class-string  $nodeClass
     */
    public static function hasConstraintFor(string $nodeClass): bool
    {
        return isset(self::$rules[$nodeClass]);
    }

    /**
     * @return iterable<class-string>
     */
    private static function walkClasses(object $o): iterable
    {
        yield $o::class;

        foreach (class_parents($o) ?: [] as $parent) {
            yield $parent;
        }

        foreach (class_implements($o) ?: [] as $interface) {
            yield $interface;
        }
    }
}
