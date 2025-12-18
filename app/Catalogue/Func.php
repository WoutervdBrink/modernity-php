<?php

namespace App\Catalogue;

use App\Language\PhpVersion;
use App\Language\PhpVersionConstraint;

/**
 * Registration of version information for a certain function.
 *
 * N.B. <code>function</code> is a keyword. Consequently, this class could not have been named <code>Function</code>.
 */
final class Func
{
    /**
     * @var array<string, list<callable(FunctionCall): PhpVersionConstraint>>
     */
    private static array $rules = [];

    /**
     * @var string Key to find the correct rule(s) in {@link self::$rules}.
     */
    private readonly string $key;

    /**
     * @param  string  $name  Name of the function.
     * @param  ?class-string  $className  Name of the class, if the function is a static method of this class.
     */
    private function __construct(
        public readonly string $name,
        public readonly ?string $className = null
    ) {
        $this->key = self::key($name, $this->className);

        self::$rules[$this->key] ??= [];
    }

    public static function key(string $name, ?string $className): string
    {
        if (! empty($className)) {
            return $className.'::'.$name;
        }

        return $name;
    }

    /**
     * Construct a new registration of version information for a certain function.
     *
     * @param  string  $name  Name of the function.
     * @param  ?class-string  $className  Name of the class, if the function is a static method of this class.
     *
     * Some examples:
     *
     * <ul>
     *     <li><code>Func::for('mysql_connect')</code>: Say something about the <code>mysql_connect()</code> function.</li>
     *     <li><code>Func::for('connect', 'MySQL')</code>: Say something about the <code>MySQL::connect()</code> method.</li>
     * </ul>
     */
    public static function for(string $name, ?string $className = null): self
    {
        return new self($name, $className);
    }

    /**
     * @return $this
     */
    public function since(PhpVersion $min): self
    {
        return $this->sinceWhen(fn (): PhpVersion => $min);
    }

    /**
     * @return $this
     */
    public function until(PhpVersion $max): self
    {
        return $this->untilWhen(fn (): PhpVersion => $max);
    }

    /**
     * @return $this
     */
    public function between(PhpVersion $min, PhpVersion $max): self
    {
        return $this->rule(fn (): PhpVersionConstraint => PhpVersionConstraint::between($min, $max));
    }

    /**
     * @param  callable(FunctionCall): ?PhpVersion  $rule
     * @return $this
     */
    public function sinceWhen(callable $rule): self
    {
        return $this->rule(static fn (FunctionCall $n): PhpVersionConstraint => PhpVersionConstraint::since($rule($n)));
    }

    /**
     * @param  callable(FunctionCall): ?PhpVersion  $rule
     * @return $this
     */
    public function untilWhen(callable $rule): self
    {
        return $this->rule(static fn (FunctionCall $n): PhpVersionConstraint => PhpVersionConstraint::until($rule($n)));
    }

    /**
     * @param  callable(FunctionCall): PhpVersionConstraint  $rule
     * @return $this
     */
    public function rule(callable $rule): self
    {
        self::$rules[$this->key][] = $rule;

        return $this;
    }

    public static function constraintFor(FunctionCall $functionCall): PhpVersionConstraint
    {
        // We cannot (yet) determine the version constraint for functions if we do not know what function was called.
        if (empty($functionCall->functionName)) {
            return PhpVersionConstraint::open();
        }

        $rules = self::$rules[self::key($functionCall->functionName, $functionCall->className)] ?? [];

        return array_reduce(
            $rules,
            fn (PhpVersionConstraint $acc, callable $rule): PhpVersionConstraint => $acc->merge($rule($functionCall)),
            PhpVersionConstraint::open()
        );
    }
}
