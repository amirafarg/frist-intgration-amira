<?php

namespace TeraDevelopers\BagistoDaftraSync\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Models\Customer;

class DaftraClientMapping extends Model
{
    //      
    protected $fillable = [
        'daftra_client_id',
        'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}