<?php

use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\EventBannerGalleryController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventManegerController;
use App\Http\Controllers\Admin\EventPublisherController;
use App\Http\Controllers\Admin\PageBuilderController;
use App\Http\Controllers\Admin\PainelController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\TemplatesController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ControllersWebsiteController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'auth'])->name('login.auth');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function() {
    Route::get('/painel', [PainelController::class, 'index'])->name('painel');
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/user/new', [UserController::class, 'create'])->name('user.new');
    Route::post('/user/save', [UserController::class, 'store'])->name('user.save');

    /**
     * events
     */
    Route::get('/events', [EventController::class, 'index'])->name('events');
    Route::get('/events/create', [EventController::class, 'create'])->name('event.create');
    Route::post('/events/store', [EventController::class, 'store'])->name('event.store');
    Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('/events/{id}/update', [EventController::class, 'update'])->name('event.update');
    Route::get('/events/{id}/delete',[EventController::class, 'delete'])->name('event.delete');

    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/user/{id}/update', [UserController::class, 'update'])->name('user.update');
    Route::get('/user/{id}/remove', [UserController::class, 'delete'])->name('user.remove');

    Route::get('/templates', [TemplatesController::class, 'index'])->name('templates');
    Route::get('/template/new', [TemplatesController::class, 'create'])->name('templates.new');
    Route::post('/templates', [TemplatesController::class, 'store'])->name('templates.save');
    Route::get('/templates/{id}/edit', [TemplatesController::class, 'edit'])->name('templates.edit');
    Route::put('/templates/{id}', [TemplatesController::class, 'update'])->name('templates.update');
    Route::get('/templates/{id}/show', [TemplatesController::class, 'show'])->name('templates.show');
    Route::delete('/templates/{id}/remove', [TemplatesController::class, 'delete'])->name('templates.destroy');

     
    /**
     * pages admin
     */
    Route::get('/evento-page/{event}/create',     [EventManegerController::class, 'create'])->name('pages.create');
    Route::post('/evento-page/{event}/create',    [EventManegerController::class, 'store'])->name('pages.store');
    Route::get('/evento-page/{event}/edit/{id}',  [EventManegerController::class, 'edit'])->name('pages.edit');
    Route::post('/evento-page/{event}/edit/{id}', [EventManegerController::class, 'update'])->name('pages.update');
    Route::get('/evento-page/{event}/delete/{id}',[EventManegerController::class, 'delete'])->name('pages.delete');

    /**
     * event-publishers
     */
    
    Route::get('/event/{id}/publishers',[EventPublisherController::class, 'index'])->name('event.publishers');
    Route::get('/event/{id}/available',[EventPublisherController::class, 'available'])->name('event.publishers.available');
    Route::post('/event/{id}/available',[EventPublisherController::class, 'attachPublisherEvent'])->name('event.publishers.available');
    Route::post('/event/{eventId}/available/{publisherId}',[EventPublisherController::class, 'detachEventPublisher'])->name('event.publishers.detach');

    /**
     * editoras|publisher
     */
    Route::get('/publisher',     [PublisherController::class, 'index'])->name('publishers');
    Route::get('/publisher/create',     [PublisherController::class, 'create'])->name('publisher.create');
    Route::get('/publisher/{id}/edit',     [PublisherController::class, 'edit'])->name('publisher.edit');
    Route::put('/publisher/{id}/update',     [PublisherController::class, 'update'])->name('publisher.update');
    Route::post('/publisher/store',     [PublisherController::class, 'store'])->name('publisher.store');
    Route::get('/publisher/{id}/delete',  [PublisherController::class, 'delete'])->name('publisher.delete');

     /**
     * editoras|publisher
     */
    Route::get('/books',     [BookController::class, 'index'])->name('books');
    Route::get('/books/create',     [BookController::class, 'create'])->name('book.create');
    Route::get('/books/{id}/edit',     [BookController::class, 'edit'])->name('book.edit');
    Route::get('/books/{id}/show',     [BookController::class, 'show'])->name('book.show');
    Route::put('/books/{id}/update',     [BookController::class, 'update'])->name('book.update');
    Route::post('/books/store',     [BookController::class, 'store'])->name('book.store');
    Route::delete('/book/{id}/delete',  [BookController::class, 'delete'])->name('book.delete');

    /**
     * event banner
     */
    Route::get('/event/{id}/banner-gallery',     [EventBannerGalleryController::class, 'index'])->name('event.banner.gallery');
    Route::get('/event/{id}/banner-gallery-create',     [EventBannerGalleryController::class, 'create'])->name('event.banner.gallery.create');
    Route::post('/event/{id}/banner-gallery-create',     [EventBannerGalleryController::class, 'store'])->name('event.banner.gallery.store');
    Route::get('/event/{id}/banner-gallery/{bannerId}/edit',     [EventBannerGalleryController::class, 'edit'])->name('event.banner.gallery.edit');
    Route::put('/event/{id}/banner-gallery/{bannerId}/update',     [EventBannerGalleryController::class, 'update'])->name('event.banner.gallery.update');
    Route::get('/event/{id}/banner-gallery/{bannerId}/show',     [EventBannerGalleryController::class, 'show'])->name('event.banner.gallery.show');
    Route::delete('/event/{id}/banner-gallery/{bannerId}/delete',     [EventBannerGalleryController::class, 'delete'])->name('event.banner.gallery.delete');

    /**
     * page builder
     */
    Route::any('/settings/pages/build', [PageBuilderController::class, 'build']);
    Route::any('/{event}/{uri}/settings/pages/build', [PageBuilderController::class, 'build']);
    Route::middleware(['check.theme'])->group(function() {
        Route::get('/gerenciar-evento/{event}', [EventManegerController::class, 'index'])->name('event.pages');
        Route::any('/settings/pages/{id}/build', [PageBuilderController::class, 'build'])->name('pagebuilder.build');
    });

    /**
     * template pages
     */
    Route::get('/template-page/{id}/create', [TemplateController::class, 'create'])->name('template.pages.create');
    Route::post('/template-page/{id}/create', [TemplateController::class, 'store'])->name('template.pages.store');
    Route::get('/template-page/{id}/edit/{pageId}', [TemplateController::class, 'edit'])->name('template.pages.edit');
    Route::put('/template-page/{id}/update/{pageId}', [TemplateController::class, 'update'])->name('template.pages.update');
    Route::get('/template-page/{id}/update/{pageId}', [TemplateController::class, 'destroy'])->name('template.pages.destroy');
    Route::any('/{template}/{uri}/settings/templates/build', [PageBuilderController::class, 'build']);
    Route::middleware(['check.theme.template'])->group(function() {
        Route::get('/template-pages/{template}', [TemplateController::class, 'index'])->name('template.pages');
        Route::any('/settings/templates/{id}/build', [PageBuilderController::class, 'build'])->name('template.pagebuilder.build');
    });
});

Route::get('/register',     [RegisterController::class, 'index'])->name('register');
Route::post('/register',    [RegisterController::class, 'create'])->name('register.create');

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['check.theme.site'])->group(function() {
    Route::get('/notfound', [ControllersWebsiteController::class, 'notfound'])->name('notfound');
    Route::any('/{domain}',         [ControllersWebsiteController::class, 'uri']);
    Route::any('/{domain}/{uri}',    [ControllersWebsiteController::class, 'uri']);
    Route::any('/{domain}/editora/{editora}', [ControllersWebsiteController::class, 'editora']);
});