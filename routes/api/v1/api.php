<?php

use App\Http\Controllers\Api\EventBannerGalleryController;
use App\Http\Controllers\Api\EventBooksController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\EventScheduleGalleryController;
use App\Http\Controllers\Api\PublisherController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'prefix' => 'v1',
    'namespace' => 'Api'
], function () {
    Route::get('/event', [EventController::class, 'index']);
    Route::get('/event-banners', [EventBannerGalleryController::class, 'index']);
    Route::get('/event-schedules', [EventScheduleGalleryController::class, 'index']);
    Route::get('/event-publishers', [PublisherController::class, 'index']);
    Route::get('/event-publisher', [PublisherController::class, 'getPublisher']);
    Route::get('/event-books', [EventBooksController::class, 'index']);
});