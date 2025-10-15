<?php

namespace Tests\Fixtures;

use DirectoryIterator;
use Generator;
use InvalidArgumentException;

readonly class ExampleRepository
{
    /** @var list<Example> */
    private array $examples;

    /**
     * @param  list<Example>  $examples
     */
    public function __construct(array $examples)
    {
        $this->examples = $examples;
    }

    /**
     * @return Generator<Example>
     */
    public function examples(): Generator
    {
        foreach ($this->examples as $example) {
            yield $example->description => $example;
        }
    }

    /**
     * Construct an example repository from its file path.
     *
     * @see ExampleRepository::fromSpec() for documentation of the specification.
     */
    public static function fromFile(string $path): self
    {
        if (! file_exists($path) || ! is_readable($path)) {
            throw new InvalidArgumentException('File '.$path.' does not exist or is not readable');
        }

        return self::fromSpec(file_get_contents($path));
    }

    /**
     * Construct an example repository from its specification.
     *
     * An example repository consists of one or more examples.
     *
     * An example consists of the line <code>#Test</code>, a new line, a list of properties, two new lines, and the
     * example code.
     *
     * Properties are formatted in the Key: Value format, with the following allowed keys:
     *
     * <ul>
     *     <li><code>Description</code> (required): A short description of the example and what it tests.</li>
     *     <li><code>Parser</code> (required): The version of the PHP parser this code should be parsed with.</li>
     *     <li><code>Min</code>: The minimum PHP version that supports parsing this code.</li>
     *     <li><code>Max</code>: The maximum PHP Version that supports parsing this code.</li>
     * </ul>
     *
     * PHP versions are to be specified in <code><major>.<minor></code> format, e.g. <code>5.6</code>.
     */
    public static function fromSpec(string $spec): self
    {
        $examples = explode("#Test\n", $spec);

        $examples = array_filter($examples);

        $examples = array_map(Example::fromSpec(...), $examples);

        return new self($examples);
    }

    /**
     * @return Generator<self>
     */
    public static function fromDirectory(string $path): Generator
    {
        if (! file_exists($path) || ! is_dir($path)) {
            throw new InvalidArgumentException('Directory '.$path.' does not exist or is not a directory');
        }

        $iterator = new DirectoryIterator($path);
        /** @var DirectoryIterator $file */
        foreach ($iterator as $file) {
            if (! $file->isFile()) {
                continue;
            }

            yield ExampleRepository::fromFile($file->getRealPath());
        }
    }
}
