<?php

use App\Providers\AppServiceProvider;
use App\Providers\CatalogueServiceProvider;
use App\Providers\ParserServiceProvider;
use GrahamCampbell\GitHub\GitHubServiceProvider;

return [
    AppServiceProvider::class,
    CatalogueServiceProvider::class,
    ParserServiceProvider::class,
    GitHubServiceProvider::class,
];
