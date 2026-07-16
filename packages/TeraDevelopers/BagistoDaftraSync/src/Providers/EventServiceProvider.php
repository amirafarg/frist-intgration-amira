<?php

namespace TeraDevelopers\BagistoDaftraSync\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\Customer\Models\Customer;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services / events.
     */
    public function boot(): void
    {
        // 1. Customer Events -> registration
        Event::listen(
            'customer.registration.after',
            'TeraDevelopers\BagistoDaftraSync\Listeners\Customer@onRegistrationAfter'
        );

        // 1. Customer Events -> update
        Event::listen(
            'customer.update.after',
            'TeraDevelopers\BagistoDaftraSync\Listeners\Customer@onUpdateAfter'
        );

        // Eloquent Model Event for customer deletion
        Customer::deleting(function ($customer) {
            app('TeraDevelopers\BagistoDaftraSync\Listeners\Customer')->onDeleting($customer);
        });

        // 2. Sales Events -> invoice
        Event::listen(
            'sales.invoice.save.after',
            'TeraDevelopers\BagistoDaftraSync\Listeners\Sales@onInvoiceSaveAfter'
        );

        // 2. Sales Events -> refund
        Event::listen(
            'sales.refund.save.after',
            'TeraDevelopers\BagistoDaftraSync\Listeners\Sales@onRefundSaveAfter'
        );
    }
}
