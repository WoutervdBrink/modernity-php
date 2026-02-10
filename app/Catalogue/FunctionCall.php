<?php

namespace App\Catalogue;

use PhpParser\Node\Arg;
use PhpParser\Node\VariadicPlaceholder;

/**
 * Detected instance of a function being called.
 */
readonly class FunctionCall
{
    /**
     * @var int Amount of arguments passed to the function.
     */
    public int $amountOfArguments;

    /**
     * @param  ?string  $functionName  Name of the function that is being called. If set to <code>null</code>, the name
     *                                 of the called function could not be determined.
     * @param  ?class-string  $className  Name of the class if the function is a static method.
     * @param  array<Arg|VariadicPlaceholder>  $arguments  Arguments passed to the function.
     */
    public function __construct(
        public ?string $functionName,
        public ?string $className,
        public array $arguments,
    ) {
        $this->amountOfArguments = count($arguments);
    }
}
