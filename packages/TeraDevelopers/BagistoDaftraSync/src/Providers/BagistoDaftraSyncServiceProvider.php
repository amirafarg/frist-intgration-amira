<?php

namespace TeraDevelopers\BagistoDaftraSync\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use TeraDevelopers\BagistoDaftraSync\Jobs\SyncInvoiceToDaftraJob;

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
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__.'/../Routes/admin-routes.php');

        $this->loadRoutesFrom(__DIR__.'/../Routes/shop-routes.php');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'bagistodaftrasync');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'bagistodaftrasync');

        Event::listen('bagisto.admin.layout.head', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('bagistodaftrasync::admin.layouts.style');
        });

        // Dispatch a background job when a Bagisto invoice is saved.
        // The job handles all Daftra API calls asynchronously so the admin
        // response is never blocked.
        Event::listen('sales.invoice.save.after', function ($invoice) {
            SyncInvoiceToDaftraJob::dispatch($invoice);
        });
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/admin-menu.php',
            'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/acl.php',
            'acl'
        );
    }
}
