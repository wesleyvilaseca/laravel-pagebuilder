<?php

namespace App\Http\Middleware;

use App\Models\Event;
use App\Models\Page;
use App\Models\Theme;
use App\Supports\Helper\Utils;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
        if (empty($params)) {
            $event = Event::where('principal', Event::PRINCIPAL_EVENT)->first();
            if (!$event) {
                $event = Event::first();
            }
        } else {
            $eventUrl = $params['domain'];
            $event = Event::where('url', $eventUrl)->first();
            if (!$event) {
                $uri = @$params['uri'];
                if (!$uri) {
                    return abort(404);
                }
    
                $page = Page::where('route', $uri)->first();
                if (!$page) {
                    return abort(404);
                }
            }
    
            if (!$event && @$page->event_id) {
                $event = Event::where('id', $page->event_id)->first();
            }    
        }

        if ($event) {
            Session::put('event', $event);
        }
        
        $theme_id = $event ? $event->theme_id : $page->templates[0]->theme_id;
        $activeThemeEvent = Theme::find($theme_id);

        config(['pagebuilder.theme.active_theme' => $activeThemeEvent->alias]);
        $pageBuilder = new PHPageBuilder(config('pagebuilder'));
        $pageBuilder->handlePublicRequest();
        
        return $next($request);
    }
}
