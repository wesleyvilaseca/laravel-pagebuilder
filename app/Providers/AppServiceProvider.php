<?php

namespace App\Providers;

use App\Models\SystemUpload;
use App\Models\UploadRelation;
use App\Services\UploadFileService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use PHPageBuilder\PHPageBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // register singleton phppagebuilder (this ensures all phpb_ helpers have the right config without first manually creating a pagebuilder instance)
        $this->app->singleton('phpPageBuilder', function ($app) {
            return new PHPageBuilder(config('pagebuilder'));
        });
        $this->app->make('phpPageBuilder');
        Schema::defaultStringLength(191);
    }
}
