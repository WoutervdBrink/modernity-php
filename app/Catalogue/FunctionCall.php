<?php

namespace App\Catalogue;

use PhpParser\Node\Arg;
use PhpParser\Node\ArgPlaceholder;
use PhpParser\Node\VariadicPlaceholder;

/**
 * Detected instance of a function being called.
 */
final readonly class FunctionCall
{
    /**
     * @var ?int Amount of arguments passed to the function.
     */
    public ?int $amountOfArguments;

    /**
     * @var bool <code>true</code> when one or more of the arguments are named.
     */
    public bool $hasNamedArguments;

    /**
     * @param  ?string  $functionName  Name of the function that is being called. If set to <code>null</code>, the name
     *                                 of the called function could not be determined.
     * @param  ?class-string  $className  Name of the class if the function is a static method.
     * @param  array<Arg|VariadicPlaceholder|ArgPlaceholder>  $arguments  Arguments passed to the function.
     */
    public function __construct(
        public ?string $functionName,
        public ?string $className,
        public array $arguments,
    ) {
        $amountOfArguments = count($arguments);
        $hasNamedArguments = false;

        foreach ($this->arguments as $argument) {
            if ($argument instanceof Arg) {
                if ($argument->unpack) {
                    $amountOfArguments = null;
                }

                if ($argument->name !== null) {
                    $hasNamedArguments = true;
                }
            }
        }

        $this->amountOfArguments = $amountOfArguments;
        $this->hasNamedArguments = $hasNamedArguments;
    }
}
