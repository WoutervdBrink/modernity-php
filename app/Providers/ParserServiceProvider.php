<?php

namespace App\Providers;

use App\Parser\CachingParserFactory;
use Illuminate\Support\ServiceProvider;
use Override;
use PhpParser\NodeTraverser;
use PhpParser\NodeTraverserInterface;
use PhpParser\ParserFactory;

final class ParserServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->app->singleton(ParserFactory::class, function () {
            return new CachingParserFactory;
        });
        $this->app->bind(NodeTraverserInterface::class, function () {
            return new NodeTraverser;
        });
    }
}
