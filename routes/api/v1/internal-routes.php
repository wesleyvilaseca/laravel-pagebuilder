<?php

use App\Http\Controllers\Admin\PublisherController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1',
    'namespace' => 'Api'
], function () {
    Route::middleware(['auth'])->group(function() {
        Route::group(['prefix' => 'admin'], function () {
            Route::put('/publisher/{id}/update',        [PublisherController::class, 'update'])->name('publisher.update');
            Route::post('/publisher/store',             [PublisherController::class, 'store'])->name('publisher.store');
            Route::get('/publisher/{id}/delete-logo',   [PublisherController::class, 'deleteLogo'])->name('publisher.delete.logo'); 
            Route::get('/publisher/{id}/delete-pricelist',   [PublisherController::class, 'deletePriceList'])->name('publisher.delete.pricelist'); 
        });
    });
});