<?php

namespace App\Http\Middleware;

use App\Models\Event;
use App\Models\Theme;
use App\Supports\Helper\Utils;
use Closure;
use Illuminate\Http\Request;
use PHPageBuilder\PHPageBuilder;

class ActiveThemeEventSiteMiddleware
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
        $eventUrl = $params['domain'];
        $event = Event::where('url', $eventUrl)->first();
        if (!$event) {
            return abort(404);
        }    
    
        $activeThemeEvent = Theme::find($event->theme_id);

        config(['pagebuilder.theme.active_theme' => $activeThemeEvent->alias]);
        $pageBuilder = new PHPageBuilder(config('pagebuilder'));
        $pageBuilder->handlePublicRequest();
        
        return $next($request);
    }
}
