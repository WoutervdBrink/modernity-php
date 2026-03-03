<?php

namespace Tests\Fixtures;

use DirectoryIterator;
use Generator;
use InvalidArgumentException;

readonly class ExampleRepository
{
    /**
     * @param  Example[]  $examples
     */
    public function __construct(public string $name, private array $examples) {}

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
    public static function fromFile(string $name, string $path): self
    {
        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new InvalidArgumentException('File '.$path.' does not exist or is not readable');
        }

        return self::fromSpec($name, $contents);
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
    public static function fromSpec(string $name, string $spec): self
    {
        $specs = explode("#Test\n", $spec);

        $specs = array_filter($specs);

        /** @var Example[] $examples */
        $examples = [];

        foreach ($specs as $spec) {
            array_push($examples, ...Example::fromSpec($spec));
        }

        return new self($name, $examples);
    }

    /**
     * @return Generator<self>
     */
    public static function fromDirectory(string $name, string $path): Generator
    {
        if (! file_exists($path) || ! is_dir($path)) {
            throw new InvalidArgumentException('Directory '.$path.' does not exist or is not a directory');
        }

        $iterator = new DirectoryIterator($path);

        foreach ($iterator as $file) {
            if (! $file->isFile()) {
                continue;
            }

            yield ExampleRepository::fromFile($name, $file->getRealPath());
        }
    }
}
