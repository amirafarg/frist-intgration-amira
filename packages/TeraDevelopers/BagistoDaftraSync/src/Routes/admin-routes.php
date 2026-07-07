<?php

use Illuminate\Support\Facades\Route;
use TeraDevelopers\BagistoDaftraSync\Http\Controllers\Admin\BagistoDaftraSyncController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/bagistodaftrasync'], function () {
    Route::controller(BagistoDaftraSyncController::class)->group(function () {
        Route::get('', 'index')->name('admin.bagistodaftrasync.index');
    });
});