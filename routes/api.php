<?php

use Illuminate\Support\Facades\Route;
use TeraDevelopers\BagistoDaftraSync\Http\Controllers\Webhooks\DaftraController;


Route::prefix('webhooks/daftra')->group(function () {
    Route::post('client-created', [DaftraController::class, 'clientCreated']);
    Route::post('client-updated', [DaftraController::class, 'clientUpdated']);
    Route::post('client-deleted', [DaftraController::class, 'clientDeleted']);
});