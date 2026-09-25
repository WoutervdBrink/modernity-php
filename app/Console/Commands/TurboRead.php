<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\HandlesIO;
use App\Turbo\TurboReader;
use LaravelZero\Framework\Commands\Command;

final class TurboRead extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'turbo:read
                            {input : The input file to parse, e.g. test.turbo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse a Turbo file and display its contents';

    use HandlesIO;

    /**
     * Execute the console command.
     */
    public function handle(TurboReader $reader): void
    {
        $path = $this->getInputPath();

        if ($path === false) {
            return;
        }

        $doc = $reader->read($path);

        $this->info('Parsed file successfully!');

        $this->info('Language: '.$doc->language);

        $this->table(['Version'], array_map(fn (string $v): array => [$v], $doc->versions));

        $this->table(['Node type'], array_map(fn (string $v): array => [$v], $doc->nodeTypes));
    }
}
