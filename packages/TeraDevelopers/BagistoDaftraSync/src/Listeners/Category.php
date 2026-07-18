<?php

namespace TeraDevelopers\BagistoDaftraSync\Listeners;

use TeraDevelopers\BagistoDaftraSync\Jobs\DeleteCategoryOnDaftraJob;
use TeraDevelopers\BagistoDaftraSync\Jobs\SyncCategoryToDaftraJob;
use TeraDevelopers\BagistoDaftraSync\Jobs\UpdateCategoryOnDaftraJob;
use TeraDevelopers\BagistoDaftraSync\Models\DaftraCategoryMapping;
use Webkul\Category\Models\Category as CategoryModel;

class Category
{
    /**
     * Handle category creation event.
     */
    public function onCategoryCreateAfter($category): void
    {
        SyncCategoryToDaftraJob::dispatch($category);
    }

    /**
     * Handle category update event.
     */
    public function onCategoryUpdateAfter($category): void
    {
        UpdateCategoryOnDaftraJob::dispatch($category);
    }

    /**
     * Handle Eloquent deleting event.
     */
    public function onDeleting(CategoryModel $category): void
    {
        $mapping = DaftraCategoryMapping::where('category_id', $category->id)->first();

        if ($mapping) {
            DeleteCategoryOnDaftraJob::dispatch($category->id, $mapping->daftra_category_id);
        }
    }
}
