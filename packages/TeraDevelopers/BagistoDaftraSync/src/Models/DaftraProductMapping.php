<?php

namespace TeraDevelopers\BagistoDaftraSync\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Product\Models\Product;

class DaftraProductMapping extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'daftra_product_mappings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'daftra_product_id',
    ];

    /**
     * The Bagisto product linked to this mapping.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
