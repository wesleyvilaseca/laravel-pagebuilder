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
    Route::get('/gerenciar-evento/{url}', [EventManegerController::class, 'index'])->name('event.pages');

    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/user/{id}/update', [UserController::class, 'update'])->name('user.update');
    Route::get('/user/{id}/remove', [UserController::class, 'delete'])->name('user.remove');

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
    Route::any('/settings/pages/{id}/build', [PageBuilderController::class, 'build'])->name('pagebuilder.build');
    Route::any('/settings/pages/build', [PageBuilderController::class, 'build']);
    Route::any('/{event}/{uri}/settings/pages/build', [PageBuilderController::class, 'build']);
});


Route::get('/register',     [RegisterController::class, 'index'])->name('register');
Route::post('/register',    [RegisterController::class, 'create'])->name('register.create');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/notfound', [ControllersWebsiteController::class, 'notfound'])->name('notfound');
Route::any('/{uri}',         [ControllersWebsiteController::class, 'uri']);
Route::any('/{domain}/{uri}',    [ControllersWebsiteController::class, 'uri']);
Route::any('/{domain}/editora/{editora}', [ControllersWebsiteController::class, 'editora']);