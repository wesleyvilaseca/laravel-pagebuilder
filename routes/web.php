<?php

use App\Http\Controllers\Admin\AuthorBookController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\EventAttachmentController;
use App\Http\Controllers\Admin\EventBannerGalleryController;
use App\Http\Controllers\Admin\EventBenchMapGalleryController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventManegerController;
use App\Http\Controllers\Admin\EventPublisherController;
use App\Http\Controllers\Admin\EventSheduleGalleryController;
use App\Http\Controllers\Admin\PageBuilderController;
use App\Http\Controllers\Admin\PainelController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PermissionRoleController;
use App\Http\Controllers\Admin\PublisherBooksController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\RoleController;
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

    /**
     * usuers
     */
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/user/new', [UserController::class, 'create'])->name('user.new');
    Route::post('/user/save', [UserController::class, 'store'])->name('user.save');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{id}/update', [UserController::class, 'update'])->name('user.update');
    Route::get('/user/{id}/remove', [UserController::class, 'delete'])->name('user.remove');

    /**
     * events
     */
    Route::get('/events', [EventController::class, 'index'])->name('events');
    Route::get('/events/create', [EventController::class, 'create'])->name('event.create');
    Route::post('/events/store', [EventController::class, 'store'])->name('event.store');
    Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('/events/{id}/update', [EventController::class, 'update'])->name('event.update');
    Route::get('/events/{id}/delete',[EventController::class, 'delete'])->name('event.delete');

    Route::get('/templates', [TemplatesController::class, 'index'])->name('templates');
    Route::get('/template/new', [TemplatesController::class, 'create'])->name('templates.new');
    Route::post('/templates', [TemplatesController::class, 'store'])->name('templates.save');
    Route::get('/templates/{id}/edit', [TemplatesController::class, 'edit'])->name('templates.edit');
    Route::put('/templates/{id}', [TemplatesController::class, 'update'])->name('templates.update');
    Route::get('/templates/{id}/show', [TemplatesController::class, 'show'])->name('templates.show');
    Route::delete('/templates/{id}/remove', [TemplatesController::class, 'delete'])->name('templates.destroy');

    /**
     * roles
     */

     Route::get('/roles', [RoleController::class, 'index'])->name('roles');
     Route::get('/role/new', [RoleController::class, 'create'])->name('role.new');
     Route::post('/role/save', [RoleController::class, 'store'])->name('role.save');
 
     Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
     Route::put('/roles/{id}/update', [RoleController::class, 'update'])->name('role.update');
     Route::get('/roles/{id}/remove', [RoleController::class, 'delete'])->name('role.remove');
 
 
     /**
      * permissions
      */
     Route::get('/role/{id}/permissions',    [PermissionRoleController::class, 'index'])->name('role.permissions');
     Route::post('/permissions/{id}/sync',   [PermissionRoleController::class, 'sync'])->name('role.permissions.sync');
 
     /**
      * permissions
      */
 
     Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions');
     Route::get('/permission/new', [PermissionController::class, 'create'])->name('permission.new');
     Route::post('/permission/save', [PermissionController::class, 'store'])->name('permission.save');
 
     Route::get('/permission/{id}/edit', [PermissionController::class, 'edit'])->name('permission.edit');
     Route::put('/permission/{id}/update', [PermissionController::class, 'update'])->name('permission.update');
     Route::get('/permission/{id}/remove', [PermissionController::class, 'delete'])->name('permission.remove');
     
    /**
     * pages admin
     */
    Route::get('/evento-page/{event}/create',     [EventManegerController::class, 'create'])->name('pages.create');
    Route::post('/evento-page/{event}/create',    [EventManegerController::class, 'store'])->name('pages.store');
    Route::get('/evento-page/{event}/edit/{id}',  [EventManegerController::class, 'edit'])->name('pages.edit');
    Route::put('/evento-page/{event}/edit/{id}', [EventManegerController::class, 'update'])->name('pages.update');
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
    Route::any('/publisher/search',            [PublisherController::class, 'index'])->name('publishers.search');
    Route::get('/publisher/create',     [PublisherController::class, 'create'])->name('publisher.create');
    Route::get('/publisher/{id}/edit',     [PublisherController::class, 'edit'])->name('publisher.edit');
    Route::put('/publisher/{id}/update',     [PublisherController::class, 'update'])->name('publisher.update');
    Route::post('/publisher/store',     [PublisherController::class, 'store'])->name('publisher.store');
    Route::get('/publisher/{id}/delete',  [PublisherController::class, 'delete'])->name('publisher.delete');

    /**
     * publisher | books
     */
    Route::get('/publisher/{url}/books',  [PublisherBooksController::class, 'index'])->name('publisher.books');
    Route::get('/publisher/{url}/book-create',  [PublisherBooksController::class, 'create'])->name('publisher.book.create');
    Route::post('/publisher/{url}/book-create',  [PublisherBooksController::class, 'store'])->name('publisher.book.store');
    Route::get('/publisher/{url}/book/{id}/edit',  [PublisherBooksController::class, 'edit'])->name('publisher.book.edit');
    Route::put('/publisher/{url}/book/{id}',  [PublisherBooksController::class, 'update'])->name('publisher.book.update');
    Route::get('/publisher/{url}/book/{id}/show',  [PublisherBooksController::class, 'show'])->name('publisher.book.show');
    Route::delete('/publisher/{url}/book/{id}/delete',  [PublisherBooksController::class, 'delete'])->name('publisher.book.delete');
    Route::delete('/publisher/{url}/books-delete',  [PublisherBooksController::class, 'deleteBatch'])->name('publisher.books.delete');
    Route::post('/publisher/{url}/book-batch-create',  [PublisherBooksController::class, 'batchStore'])->name('publisher.books.batch');



    /**
     * author|books
     */
    Route::get('/author',     [AuthorController::class, 'index'])->name('authors');
    Route::get('/author/create',     [AuthorController::class, 'create'])->name('author.create');
    Route::get('/author/{id}/edit',     [AuthorController::class, 'edit'])->name('author.edit');
    Route::put('/author/{id}/update',     [AuthorController::class, 'update'])->name('author.update');
    Route::post('/author/store',     [AuthorController::class, 'store'])->name('author.store');
    Route::get('/author/{id}/delete',  [AuthorController::class, 'delete'])->name('author.delete');

    /**
     * book-authors
     */
    Route::get('/book/{id}/authors',[AuthorBookController::class, 'index'])->name('book.authors');
    Route::get('/book/{id}/available',[AuthorBookController::class, 'available'])->name('book.authors.available');
    Route::post('/book/{id}/available',[AuthorBookController::class, 'attachPublisherEvent'])->name('book.authors.available');
    Route::post('/book/{bookId}/available/{authorId}',[AuthorBookController::class, 'detachEventPublisher'])->name('book.authors.detach');

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
     * event attachments
     */
    Route::get('/event/{id}/attachments',     [EventAttachmentController::class, 'index'])->name('event.attachments');
    Route::get('/event/{id}/attachment-create',     [EventAttachmentController::class, 'create'])->name('event.attachment.create');
    Route::post('/event/{id}/attachment-create',     [EventAttachmentController::class, 'store'])->name('event.attachment.store');
    Route::get('/event/{id}/attachment/{bannerId}/edit',     [EventAttachmentController::class, 'edit'])->name('event.attachment.edit');
    Route::put('/event/{id}/attachment/{bannerId}/update',     [EventAttachmentController::class, 'update'])->name('event.attachment.update');
    Route::get('/event/{id}/attachment/{bannerId}/show',     [EventAttachmentController::class, 'show'])->name('event.attachment.show');
    Route::delete('/event/{id}/attachment/{bannerId}/delete',     [EventAttachmentController::class, 'delete'])->name('event.attachment.delete');
    
    /**
     * event benchmap
     */
    Route::get('/event/{id}/benchmap',     [EventBenchMapGalleryController::class, 'index'])->name('event.benchmap.gallery');
    Route::get('/event/{id}/benchmap-create',     [EventBenchMapGalleryController::class, 'create'])->name('event.benchmap.gallery.create');
    Route::post('/event/{id}/benchmap-create',     [EventBenchMapGalleryController::class, 'store'])->name('event.benchmap.gallery.store');
    Route::get('/event/{id}/benchmap/{benchmapId}/edit',     [EventBenchMapGalleryController::class, 'edit'])->name('event.benchmap.gallery.edit');
    Route::put('/event/{id}/benchmap/{benchmapId}/update',     [EventBenchMapGalleryController::class, 'update'])->name('event.benchmap.gallery.update');
    Route::get('/event/{id}/benchmap/{benchmapId}/show',     [EventBenchMapGalleryController::class, 'show'])->name('event.benchmap.gallery.show');
    Route::delete('/event/{id}/benchmap/{benchmapId}/delete',     [EventBenchMapGalleryController::class, 'delete'])->name('event.benchmap.gallery.delete');

     /**
     * event schedule
     */
    Route::get('/event/{id}/schedule',     [EventSheduleGalleryController::class, 'index'])->name('event.schedule.gallery');
    Route::get('/event/{id}/schedule-create',     [EventSheduleGalleryController::class, 'create'])->name('event.schedule.gallery.create');
    Route::post('/event/{id}/schedule-create',     [EventSheduleGalleryController::class, 'store'])->name('event.schedule.gallery.store');
    Route::get('/event/{id}/schedule/{scheduleId}/edit',     [EventSheduleGalleryController::class, 'edit'])->name('event.schedule.gallery.edit');
    Route::put('/event/{id}/schedule/{scheduleId}/update',     [EventSheduleGalleryController::class, 'update'])->name('event.schedule.gallery.update');
    Route::get('/event/{id}/schedule/{scheduleId}/show',     [EventSheduleGalleryController::class, 'show'])->name('event.schedule.gallery.show');
    Route::delete('/event/{id}/schedule/{scheduleId}/delete',     [EventSheduleGalleryController::class, 'delete'])->name('event.schedule.gallery.delete');

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



Route::middleware(['check.theme.site'])->group(function() {
    Route::any('{any}', [ControllersWebsiteController::class, 'uri'])->where('any', '.*');
    Route::get('/notfound', [ControllersWebsiteController::class, 'notfound'])->name('notfound');
    /**
     * principal navigation
     */
    // Route::get('/', [ControllersWebsiteController::class, 'index']);
    // Route::get('/editoras', [ControllersWebsiteController::class, 'editoras']);
    // Route::get('/editora/{editora}', [ControllersWebsiteController::class, 'editora']);

    /**
     * subomain
     */
    // Route::any('/{domain}',         [ControllersWebsiteController::class, 'domain']);
    // Route::any('/{domain}/{uri}',    [ControllersWebsiteController::class, 'domainUri']);
    // Route::any('/{domain}/editora/{editora}', [ControllersWebsiteController::class, 'editora']);
});

// Route::get('/', function () {
//     return view('welcome');
// });