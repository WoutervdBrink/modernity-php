<?php

namespace App\Catalogue;

/**
 * Detected instance of a function being called.
 */
readonly class FunctionCall
{
    /**
     * @param  ?string  $functionName  Name of the function that is being called. If set to <code>null</code>, the name
     *                                 of the called function could not be determined.
     * @param  ?class-string  $className  Name of the class if the function is a static method.
     * @param  int  $arguments  Amount of arguments passed to the function.
     */
    public function __construct(
        public ?string $functionName,
        public ?string $className,
        public int $arguments,
    ) {}
}
