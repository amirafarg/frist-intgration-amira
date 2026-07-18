<?php

namespace TeraDevelopers\BagistoDaftraSync\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Category\Models\Category;

class DaftraCategoryMapping extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'daftra_category_mappings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'daftra_category_id',
    ];

    /**
     * Get the category that owns the mapping.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
