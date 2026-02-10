<?php

namespace Tests\Fixtures;

use App\Language\PhpVersion;
use InvalidArgumentException;
use PhpParser\PhpVersion as ParserVersion;

readonly class Example
{
    public function __construct(
        public ParserVersion $parserVersion,
        public string $code,
        public ?string $description,
        public ?PhpVersion $minVersion,
        public ?PhpVersion $maxVersion,
        public bool $everyLine,
    ) {
        //
    }

    /**
     * Construct an example from its specification.
     *
     * N.B. This method assumes that <code>#Test</code> has already been stripped from the specification.
     *
     * @see ExampleRepository::fromSpec() for documentation of the specification.
     */
    public static function fromSpec(string $spec): self
    {
        $spec = explode("\n\n", $spec, 2);
        if (count($spec) !== 2) {
            var_dump($spec);
            throw new InvalidArgumentException('Specification of an example must contain two consecutive newlines.');
        }

        [$specProperties, $specCode] = $spec;

        $specProperties = explode("\n", $specProperties);
        $properties = [];

        foreach ($specProperties as $property) {
            if (! preg_match('/^([A-Za-z]+): (.+)$/', $property, $matches)) {
                throw new InvalidArgumentException('Invalid test property: '.$property);
            }

            [, $key, $value] = $matches;

            if (isset($properties[$key])) {
                throw new InvalidArgumentException('Test property '.$key.'was defined earlier');
            }

            $properties[$key] = $value;
        }

        if (! isset($properties['Description'], $properties['Parser'])) {
            throw new InvalidArgumentException('Test must have a defined Description and Parser.');
        }

        $parserVersion = ParserVersion::fromString($properties['Parser']);

        $code = trim($specCode);

        if (! empty($properties['Min'])) {
            $minVersion = PhpVersion::fromVersionString($properties['Min']);
        } else {
            $minVersion = null;
        }

        if (! empty($properties['Max'])) {
            $maxVersion = PhpVersion::fromVersionString($properties['Max']);
        } else {
            $maxVersion = null;
        }

        $everyLine = ! empty($properties['EveryLine']) && $properties['EveryLine'] === 'true';

        return new self(
            $parserVersion,
            $code,
            $properties['Description'],
            $minVersion,
            $maxVersion,
            $everyLine,
        );
    }

    public function __toString(): string
    {
        return 'lmao';
    }
}
