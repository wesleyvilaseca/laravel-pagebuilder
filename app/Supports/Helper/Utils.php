<?php

namespace App\Supports\Helper;
class Utils
{
    public static function back_route_pagebuilder()
    {
        $event = @$_GET['event'] ?? null;
        $route =  env('APP_URL') . '/gerenciar-evento/' . $event;
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

    public static function getActiveTheme() {
        return env('ACTIVE_THEME');
    }
}