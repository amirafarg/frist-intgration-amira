<?php

namespace TeraDevelopers\BagistoDaftraSync\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use TeraDevelopers\BagistoDaftraSync\Services\InvoiceSyncService;

class BagistoDaftraSyncServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'bagistodaftrasync');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'bagistodaftrasync');

        Event::listen('bagisto.admin.layout.head', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('bagistodaftrasync::admin.layouts.style');
        });

        Event::listen('sales.invoice.save.after', [InvoiceSyncService::class, 'handle']);
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php',
            'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php',
            'acl'
        );
    }
}