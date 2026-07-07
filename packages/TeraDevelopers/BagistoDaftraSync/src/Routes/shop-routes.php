<?php

use Illuminate\Support\Facades\Route;
use TeraDevelopers\BagistoDaftraSync\Http\Controllers\Shop\BagistoDaftraSyncController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency'], 'prefix' => 'bagistodaftrasync'], function () {
    Route::get('', [BagistoDaftraSyncController::class, 'index'])->name('shop.bagistodaftrasync.index');
});