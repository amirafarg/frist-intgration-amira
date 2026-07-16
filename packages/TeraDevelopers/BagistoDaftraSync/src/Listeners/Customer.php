<?php

namespace TeraDevelopers\BagistoDaftraSync\Listeners;

use TeraDevelopers\BagistoDaftraSync\Jobs\DeleteClientOnDaftraJob;
use TeraDevelopers\BagistoDaftraSync\Jobs\SyncCustomerToDaftraJob;
use TeraDevelopers\BagistoDaftraSync\Jobs\UpdateClientOnDaftraJob;
use TeraDevelopers\BagistoDaftraSync\Models\DaftraClientMapping;
use Webkul\Customer\Models\Customer as CustomerModel;

class Customer
{
    /**
     * Handle customer registration event.
     */
    public function onRegistrationAfter($customer): void
    {
        SyncCustomerToDaftraJob::dispatch($customer);
    }

    /**
     * Handle customer profile update event.
     */
    public function onUpdateAfter($customer): void
    {
        UpdateClientOnDaftraJob::dispatch($customer);
    }

    /**
     * Handle Eloquent deleting event.
     */
    public function onDeleting(CustomerModel $customer): void
    {
        $mapping = DaftraClientMapping::where('customer_id', $customer->id)->first();

        if ($mapping) {
            DeleteClientOnDaftraJob::dispatch($customer->id, $mapping->daftra_client_id);
        }
    }
}
