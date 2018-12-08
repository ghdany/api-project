<?php
declare(strict_types=1);

namespace JournalMedia\Sample\Http;

use Illuminate\Container\Container;
use JournalMedia\Sample\Http\Controller\PublicationRiverController;
use JournalMedia\Sample\Http\Controller\TagRiverController;
use League\Route\RouteCollection;

class ServiceProvider
{
    public function register(Container $container): void
    {
        $container->singleton(RouteCollection::class, function ($container) {
            $route = new RouteCollection;

            $route->get("/", [$container[PublicationRiverController::class], 'index']);
            $route->get("/{tag}", [$container[TagRiverController::class], 'index']);

            return $route;
        });
    }
}
