<?php

use App\Providers\AppServiceProvider;
use App\Providers\CatalogueServiceProvider;
use App\Providers\ParserServiceProvider;
use App\Providers\TypeScriptTransformerServiceProvider;
use GrahamCampbell\GitHub\GitHubServiceProvider;

return [
    AppServiceProvider::class,
    CatalogueServiceProvider::class,
    ParserServiceProvider::class,
    TypeScriptTransformerServiceProvider::class,
    GitHubServiceProvider::class,
];
