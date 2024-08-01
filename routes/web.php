<?php

use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventManegerController;
use App\Http\Controllers\Admin\PageBuilderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PainelController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PermissionRoleController;
use App\Http\Controllers\Admin\PostController;
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
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/user/new', [UserController::class, 'create'])->name('user.new');
    Route::post('/user/save', [UserController::class, 'store'])->name('user.save');

    Route::get('/eventos', [EventController::class, 'index'])->name('events');

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