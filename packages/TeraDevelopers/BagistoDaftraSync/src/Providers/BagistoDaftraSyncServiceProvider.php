<?php

namespace TeraDevelopers\BagistoDaftraSync\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use TeraDevelopers\BagistoDaftraSync\Jobs\DeleteClientOnDaftraJob;
use TeraDevelopers\BagistoDaftraSync\Jobs\SyncCustomerToDaftraJob;
use TeraDevelopers\BagistoDaftraSync\Jobs\SyncInvoiceToDaftraJob;
use TeraDevelopers\BagistoDaftraSync\Jobs\UpdateClientOnDaftraJob;
use TeraDevelopers\BagistoDaftraSync\Models\DaftraClientMapping;
use Webkul\Customer\Models\Customer;

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

        // Dispatch a background job when a customer registers.
        // The job syncs the new customer to Daftra asynchronously so the
        // registration HTTP response is never blocked.
        Event::listen('customer.registration.after', function ($customer) {
            SyncCustomerToDaftraJob::dispatch($customer);
        });

        // Dispatch a background job when a customer profile is updated.
        // Covers both admin edits (/admin/customers) and storefront profile
        // edits (/customer/account/profile/edit) — both fire the same event.
        Event::listen('customer.update.after', function ($customer) {
            UpdateClientOnDaftraJob::dispatch($customer);
        });

        // Listen BEFORE the customer is deleted using the generic Eloquent model event.
        // This is required because Bagisto's individual "Delete" button does NOT fire
        // the custom 'customer.delete.before' event (only mass-delete does).
        // The job cleans up the local mapping AND removes the client on Daftra.
        Customer::deleting(function ($customer) {
            $mapping = DaftraClientMapping::where('customer_id', $customer->id)->first();

            if ($mapping) {
                DeleteClientOnDaftraJob::dispatch($customer->id, $mapping->daftra_client_id);
            }
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
