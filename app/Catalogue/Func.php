<?php

namespace App\Catalogue;

use App\Language\PhpVersion;
use App\Language\PhpVersionConstraint;
use Override;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use UnexpectedValueException;

/**
 * Registration of version information for a certain function.
 *
 * N.B. <code>function</code> is a keyword. Consequently, this class could not have been named <code>Function</code>.
 */
final class Func extends IdentifierCatalogue
{
    /**
     * @var array<string, list<callable(FunctionCall): PhpVersionConstraint>>
     */
    private static array $rules = [];

    /**
     * @param  string  $name  Name of the function.
     * @param  ?class-string  $className  Name of the class, if the function is a static method of this class.
     */
    protected function __construct(string $name, ?string $className = null)
    {
        parent::__construct(self::key($name, $className));

        self::$rules[$this->name] ??= [];
    }

    private static function key(string $name, ?string $className): string
    {
        if (! empty($className)) {
            return $className.'::'.$name;
        }

        return $name;
    }

    /**
     * Process a row from the catalogue and load it.
     *
     * @param  string[]  $row
     */
    #[Override]
    protected static function processFromCatalogue(array $row): void
    {
        if (count($row) < 3) {
            throw new UnexpectedValueException('A row in the constants database should have at least 3 columns; it has '.count($row).'.');
        }

        if (count($row) < 4) {
            $row[] = '';
        }

        [$name, $class, $since, $until] = $row;

        if ($class === '') {
            $func = self::for($name);
        } else {
            /** @var class-string $class */
            $func = self::forClass($name, $class);
        }

        if (! empty($since)) {
            $func->since(PhpVersion::fromVersionString($since));
        }
        if (! empty($until)) {
            $func->until(PhpVersion::fromVersionString($until));
        }
    }

    /**
     * Construct a new registration of version information for a certain static method.
     *
     * @param  string  $name  Name of the function.
     * @param  class-string  $className  Name of the class.
     *
     * @see static::for() to register plain functions.
     *
     * @example <code>Func::forClass('connect', 'MySQL')</code>: Say something about the <code>MySQL::connect()</code> method.
     */
    public static function forClass(string $name, string $className): self
    {
        return new self($name, $className);
    }

    /**
     * @param  callable(FunctionCall): ?PhpVersion  $rule
     * @return $this
     */
    public function sinceWhen(callable $rule): self
    {
        return $this->rule(static fn (FunctionCall $call): PhpVersionConstraint => PhpVersionConstraint::since($rule($call)));
    }

    /**
     * Register minimum/maximum versions depending on the amount of arguments passed to a function.
     *
     * @param  callable(int): bool  $argumentsRule  Callback function receiving the amount of arguments that were passed.
     * @param  PhpVersion|null  $since  Minimum version if <code>$argumentsRule</code> returns <code>true</code>.
     * @param  PhpVersion|null  $until  Maximum version if <code>$argumentsRule</code> returns <code>true</code>.
     * @param  PhpVersion|null  $sinceOtherwise  Minimum version if <code>$argumentsRule</code> returns <code>false</code>.
     * @param  PhpVersion|null  $untilOtherwise  Maximum version if <code>$argumentsRule</code> returns <code>false</code>.
     * @return $this
     */
    public function arguments(
        callable $argumentsRule,
        ?PhpVersion $since = null,
        ?PhpVersion $until = null,
        ?PhpVersion $sinceOtherwise = null,
        ?PhpVersion $untilOtherwise = null,
    ): self {
        return $this->rule(fn (FunctionCall $call): PhpVersionConstraint => $argumentsRule($call->amountOfArguments)
                ? PhpVersionConstraint::between($since, $until)
                : PhpVersionConstraint::between($sinceOtherwise, $untilOtherwise)
        );
    }

    /**
     * Register minimum/maximum versions depending on the type of an argument passed to a function.
     *
     * Whether the argument is of the requested type is compared using <code>instanceof</code>.
     *
     * @param  int  $argumentIndex  Index of the argument in the function signature. Starts at zero.
     * @param  class-string<Expr>  $argumentType  Type of the argument.
     * @param  PhpVersion|null  $since  Minimum version if the given argument is of type <code>$argumentType</code>.
     * @param  PhpVersion|null  $until  Maximum version if the given argument is of type <code>$argumentType</code>.
     * @param  PhpVersion|null  $sinceOtherwise  Minimum version if the given argument is not of type <code>$argumentType</code>.
     * @param  PhpVersion|null  $untilOtherwise  Maximum version if the given argument is not of type <code>$argumentType</code>.
     * @return $this
     */
    public function argumentType(
        int $argumentIndex,
        string $argumentType,
        ?PhpVersion $since = null,
        ?PhpVersion $until = null,
        ?PhpVersion $sinceOtherwise = null,
        ?PhpVersion $untilOtherwise = null,
    ) {
        return $this->rule(fn (FunctionCall $call): PhpVersionConstraint => (
            $call->arguments[$argumentIndex] instanceof Arg &&
            $call->arguments[$argumentIndex]->value instanceof $argumentType
        )
                ? PhpVersionConstraint::between($since, $until)
                : PhpVersionConstraint::between($sinceOtherwise, $untilOtherwise)
        );
    }

    /**
     * Register minimum/maximum versions depending on the presence of an option in an <code>options</code> argument.
     *
     * An <code>options</code> argument is a key-value array that is passed to a function to change its functionality.
     * The available keys might change depending on the PHP version. This method simplifies checking such changes.
     *
     * @param  int  $optionsArgumentIndex  Index of the <code>options</code> argument in the function signature. Starts at zero.
     * @param  string  $option  Name of the option.
     * @param  PhpVersion|null  $since  Minimum version if the option is used.
     * @param  PhpVersion|null  $until  Maximum version if the option is used.
     * @param  PhpVersion|null  $sinceOtherwise  Minimum version if the option is used.
     * @param  PhpVersion|null  $untilOtherwise  Maximum version if the option is used.
     * @return $this
     *
     * @example <code>Func::for('foo')->option(1, 'bar', since: PhpVersion::PHP_7_4)</code>: calling <code>foo</code>
     * with the <code>bar</code> option (e.g. <code>foo(null, ['bar' => 'baz']);</code>) has been allowed since PHP 7.4.
     */
    public function option(
        int $optionsArgumentIndex,
        string $option,
        ?PhpVersion $since = null,
        ?PhpVersion $until = null,
        ?PhpVersion $sinceOtherwise = null,
        ?PhpVersion $untilOtherwise = null,
    ): self {
        $check = function (FunctionCall $call) use ($optionsArgumentIndex, $option): bool {
            if ($call->amountOfArguments < ($optionsArgumentIndex + 1)) {
                return false;
            }

            $arg = $call->arguments[$optionsArgumentIndex];
            if (! $arg instanceof Arg) {
                return false;
            }

            $val = $arg->value;
            if (! $val instanceof Array_) {
                return false;
            }

            foreach ($val->items as $item) {
                if ($item->key instanceof String_ && $item->key->value === $option) {
                    return true;
                }
            }

            return false;
        };

        return $this->rule(fn (FunctionCall $call): PhpVersionConstraint => $check($call)
                ? PhpVersionConstraint::between($since, $until)
                : PhpVersionConstraint::between($sinceOtherwise, $untilOtherwise)
        );
    }

    /**
     * @param  callable(FunctionCall): ?PhpVersion  $rule
     * @return $this
     */
    public function untilWhen(callable $rule): self
    {
        return $this->rule(static fn (FunctionCall $call): PhpVersionConstraint => PhpVersionConstraint::until($rule($call)));
    }

    /**
     * @param  callable(int): ?PhpVersion  $argumentsRule
     * @return $this
     */
    public function untilWhenArguments(callable $argumentsRule): self
    {
        return $this->untilWhen(static fn (FunctionCall $call): ?PhpVersion => $argumentsRule($call->amountOfArguments));
    }

    /**
     * @param  callable(FunctionCall): PhpVersionConstraint  $rule
     * @return $this
     */
    public function rule(callable $rule): self
    {
        self::$rules[$this->name][] = $rule;

        return $this;
    }

    public static function constraintForFunctionCall(FunctionCall $functionCall): PhpVersionConstraint
    {
        // We cannot (yet) determine the version constraint for functions if we do not know what function was called.
        if (empty($functionCall->functionName)) {
            return PhpVersionConstraint::open();
        }

        $baseVersionConstraint = self::constraintFor(self::key($functionCall->functionName, $functionCall->className));

        $rules = self::$rules[self::key($functionCall->functionName, $functionCall->className)] ?? [];

        return array_reduce(
            $rules,
            fn (PhpVersionConstraint $acc, callable $rule): PhpVersionConstraint => $acc->merge($rule($functionCall)),
            $baseVersionConstraint,
        );
    }
}
