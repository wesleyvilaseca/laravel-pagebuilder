<?php

namespace App\Supports\Helper;

use App\Models\Site;
use App\Models\TenantSites;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class Utils
{
    public static function back_route_pagebuilder()
    {
        $id = @$_GET['event'] ? $_GET['event'] : 0;
        $route =  '/gerenciar-evento/' . $id;
        return $route;
    }

    public static function getasset($route)
    {
        return asset($route);
    }


    public static function get_site_url()
    {
        return url('/');
    }

    public static function getPageRoute() {
        $event = @$_GET['event'] ?? null;
        $page = @$_GET['page']  ?? null;
        $route =  env('APP_URL') . '/' . $event . '/' . $page;
        return $route;
    }
}