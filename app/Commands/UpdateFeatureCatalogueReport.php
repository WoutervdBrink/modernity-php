<?php

namespace App\Commands;

use App\Commands\Support\FeatureCatalogueStatusReport;
use App\Feature;
use Exception;
use HaydenPierce\ClassFinder\ClassFinder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use LaravelZero\Framework\Commands\Command;
use ReflectionClass;
use ReflectionException;

final class UpdateFeatureCatalogueReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catalogue:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the feature catalogue coverage report';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        /** @var class-string[] $classes */
        $classes = ClassFinder::getClassesInNamespace('PhpParser\Node\\', ClassFinder::RECURSIVE_MODE);

        $reports = array_map(self::inspectClass(...), $classes);

        $report = View::make('reports.feature-catalogue', compact('reports'))->render();

        Storage::disk('reports')->put('feature-catalogue.html', $report);
    }

    /**
     * @param  class-string  $className
     *
     * @throws ReflectionException
     */
    private function inspectClass(string $className): FeatureCatalogueStatusReport
    {
        $rf = new ReflectionClass($className);

        if ($rf->isAbstract()) {
            return FeatureCatalogueStatusReport::isAbstract($className);
        }

        if (Feature::hasConstraintFor($className)) {
            return FeatureCatalogueStatusReport::implemented($className);
        }

        $parents = [];
        $cursor = $rf;

        while ($parent = $cursor->getParentClass()) {
            $parents[] = $parent->getName();
            $cursor = $parent;
        }

        $superclasses = array_filter($parents, fn (string $parent): bool => Feature::hasConstraintFor($parent));

        if (! empty($superclasses)) {
            return FeatureCatalogueStatusReport::implementedBySuperclass($className, $superclasses);
        }

        return FeatureCatalogueStatusReport::notImplemented($className);
    }
}
