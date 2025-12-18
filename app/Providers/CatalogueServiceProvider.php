<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

final class CatalogueServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadCatalogue('constants');
        $this->loadCatalogue('expressions');
        $this->loadCatalogue('functions');
        $this->loadCatalogue('names');
        $this->loadCatalogue('scalars');
        $this->loadCatalogue('statements');
        $this->loadCatalogue('others');
    }

    private function loadCatalogue(string $name): void
    {
        $path = base_path('catalogue/'.$name.'.php');

        if (! file_exists($path) || ! is_readable($path)) {
            throw new InvalidArgumentException('Catalogue file '.$name.' does not exist or is not readable');
        }

        require_once $path;
    }
}
