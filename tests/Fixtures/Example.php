<?php

namespace Tests\Fixtures;

use App\Language\PhpVersion;
use InvalidArgumentException;
use PhpParser\PhpVersion as ParserVersion;

final readonly class Example
{
    private function __construct(
        public ParserVersion $parserVersion,
        public string $code,
        public ?string $description,
        public PhpVersion|null|false $minVersion,
        public PhpVersion|null|false $maxVersion,
    ) {
        //
    }

    /**
     * Construct an example from its specification.
     *
     * N.B. This method assumes that <code>#Test</code> has already been stripped from the specification.
     *
     * @see ExampleRepository::fromSpec() for documentation of the specification.
     *
     * @return self[]
     */
    public static function fromSpec(string $spec): array
    {
        $spec = explode("\n\n", $spec, 2);
        if (count($spec) !== 2) {
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

        $minVersion = ! empty($properties['Min'])
            ? (($properties['Min'] === 'none')
                ? null
                : PhpVersion::fromVersionString($properties['Min']))
            : false;

        $maxVersion = ! empty($properties['Max'])
            ? (($properties['Max'] === 'none')
                ? null
                : PhpVersion::fromVersionString($properties['Max']))
            : false;

        $everyLine = ! empty($properties['EveryLine']) && $properties['EveryLine'] === 'true';

        if ($everyLine) {
            // The example has EveryLine: true, meaning **every** line in the code has to conform to the expectations.
            $codes = explode("\n", $code);
            // Shift '<?php' from the start of the code.
            $start = array_shift($codes);
            // Pop '? >' from the end of the code.
            if (trim(array_pop($codes)) !== '?>') {
                throw new InvalidArgumentException('Last line in an EveryLine example MUST be the PHP closing tag!');
            }
            // Zip the starting line to every line in the example.
            $codes = array_map(fn (string $code): string => $start."\n".$code, $codes);

            return array_map(fn (string $code, int $idx): self => new self(
                $parserVersion,
                $code,
                $properties['Description'].' (Line '.($idx + 1).')',
                $minVersion,
                $maxVersion,
            ), $codes, array_keys($codes));
        }

        return [new self(
            $parserVersion,
            $code,
            $properties['Description'],
            $minVersion,
            $maxVersion,
        )];
    }
}
