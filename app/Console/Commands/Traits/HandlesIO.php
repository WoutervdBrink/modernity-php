<?php

namespace App\Console\Commands\Traits;

trait HandlesIO
{
    private function getInputPath(): string|false
    {
        $input = $this->argument('input');

        if (! is_string($input)) {
            $this->error('The input argument must be a string.');

            return false;
        }

        if (! file_exists($input) || ! is_readable($input)) {
            $this->error('Input file '.$input.' does not exist or is not readable');

            return false;
        }

        return $input;
    }

    private function getInput(): string|false
    {
        $path = $this->getInputPath();

        if (! $path) {
            return false;
        }

        return file_get_contents($path);
    }

    private function getOutputPath(): string|false
    {
        $output = $this->argument('output');

        if (! is_string($output)) {
            $this->error('The output argument must be a string.');

            return false;
        }

        if (file_exists($output)) {
            if (! $this->option('overwrite') && ! $this->confirm('Output file '.$output.' exists. Overwrite?')) {
                return false;
            }
        }

        if (! touch($output) || ! is_writable($output)) {
            $this->error('Output file '.$output.' is not writable');

            return false;
        }

        return $output;
    }
}
