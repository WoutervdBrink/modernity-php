<?php

namespace App\Commands;

use App\Feature;
use Exception;
use HaydenPierce\ClassFinder\ClassFinder;
use Illuminate\Support\Collection;
use LaravelZero\Framework\Commands\Command;
use ReflectionClass;

final class CheckFeatureCatalogue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catalogue:check
                            {--T|type=} : The type of node classes to check, e.g. "Expr"
                            {--R|recursive} : Search recursively for node classes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check feature catalogue coverage';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        $namespace = 'PhpParser\Node\\';
        if ($this->option('type') !== null) {
            /** @var string $type */
            $type = $this->argument('type');
            $namespace .= $type.'\\';
        }

        $options = $this->option('recursive') ? ClassFinder::RECURSIVE_MODE : ClassFinder::STANDARD_MODE;
        /** @var class-string[] $classes */
        $classes = ClassFinder::getClassesInNamespace($namespace, $options);

        $defined = [];
        $undefined = [];

        foreach ($classes as $class) {
            $rf = new ReflectionClass($class);

            if ($rf->isAbstract()) {
                continue;
            }

            if (Feature::hasConstraintFor($class)) {
                $defined[] = $class;
            } else {
                $undefined[] = $class;
            }
        }

        collect($defined)->whenNotEmpty(function (Collection $collection) {
            $this->info('The following node classes are known in the catalogue:');

            $collection->each(function ($class) {
                $this->line(' - '.$class);
            });
        });

        collect($undefined)->whenNotEmpty(function (Collection $collection) {
            $this->warn('The following node classes are not known in the catalogue:');

            $collection->each(function ($class) {
                $this->warn(' - '.$class);
            });
        });
    }
}
