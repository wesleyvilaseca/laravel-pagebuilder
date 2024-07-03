<?php

namespace App\Http\Middleware;

use App\Models\Event;
use App\Models\Theme;
use App\Supports\Helper\Utils;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use PHPageBuilder\PHPageBuilder;

class ActiveThemeEventMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $params = $request->route()->parameters();
        if(@$params['event'])  {
            $eventUrl = $params['event'];
        }

        if (@$_GET['event']) {
            $eventUrl = $_GET['event'];
        }
        
        if(!$eventUrl) {
            return abort(404);
        }

        $event = Event::where('url', $eventUrl)->first();
        if (!$event) {
            return abort(404);
        }

        $activeThemeEvent = Theme::find($event->theme_id);

        /**
         * caso não seja a página do builder
         */
        if(@$params['event'])  {
            $_SESSION[$eventUrl] = $activeThemeEvent->alias;
            return $next($request);
        }

        $themeSession = @$_SESSION[$eventUrl];
        if ($activeThemeEvent->alias == config('pagebuilder.theme.active_theme') || $themeSession == $activeThemeEvent->alias) {
            return $next($request);
        }

        $_SESSION[$eventUrl] = $activeThemeEvent->alias;
        
        return Redirect::back();

    }
}
