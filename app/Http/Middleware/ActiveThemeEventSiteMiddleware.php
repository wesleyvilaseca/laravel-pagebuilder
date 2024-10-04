<?php

namespace App\Http\Middleware;

use App\Models\Event;
use App\Models\Page;
use App\Models\Template;
use App\Models\Theme;
use App\Supports\Helper\Utils;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
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
        Session::forget('event');

        $url = explode('/', str_replace([env('APP_URL'), 'http://', 'https://'], "", request()->url()));
        $url = array_values(array_filter($url, function($value) {  return $value !== ""; }));

        if(in_array(Page::URL_PAGE_PUBLISHERS, $url)) {
            return Redirect::back();
        };

        //acess principal event
        if(empty($url)) {
            $event = Event::where('principal', Event::PRINCIPAL_EVENT)->first();
            if (!$event) {
                $event = Event::first();
            }

            Session::put('event', $event);

            $theme = Theme::find($event->theme_id);
            return $this->proccess($request, $next, $theme);
        }

        //if is template an try acess without page uri
        $template = Template::where('url', $url[0])->first();
        if($template && sizeof($url) <= 1) {
            return abort(404);
        }

        //is template and has page name
        if($template && sizeof($url) > 1) {
            $page = $template->pages->where('route', end($url))->first();
            //if template dont has the page
            if (!$page) {
                return abort(404);
            }

            $theme = Theme::find($template->theme_id);
            return $this->proccess($request, $next, $theme);
        }

        $event = Event::where('url', $url[0])->first();
        //is event and try acess with a subdomain
        if($event && sizeof($url) > 1) {
            if(in_array("editora", $url)) {
                $page = Page::where(['event_id' => $event->id, 'route' =>'editora'])->first();
            } else {
                $page = Page::where(['route' => end($url), 'event_id' => $event->id])->first();
            }

            //if page dont exists
            if (!$page) {
                return abort(404);
            }

            Session::put('event', $event);

            $theme = Theme::find($event->theme_id);

            return $this->proccess($request, $next, $theme);
        }

        //if is event and try access without the page uri
        if($event && sizeof($url) <= 1) {
            $page = Page::where(['event_id' => $event->id, 'homepage' => Page::HOME_PAGE])->first();
            //if dont has defined home page
            if (!$page) {
                $page = Page::where(['event_id' => $event->id])->first();
                //if not has page
                if (!$page) {
                    return abort(404);
                }
            }

            Session::put('event', $event);

            $theme = Theme::find($event->theme_id);

            return $this->proccess($request, $next, $theme);
        }

        //if !event e not a template try to access a page from a principal event
        $event = Event::where('principal', Event::PRINCIPAL_EVENT)->first();
        $page = Page::where(['route' => end($url), 'event_id' => $event->id])->first();
        if(!$page) {
            return abort(404);
        }
        
        Session::put('event', $event);
        $theme = Theme::find($event->theme_id);

        return $this->proccess($request, $next, $theme);
    }

    private function proccess(Request $request, Closure $next, Theme $theme) {
        config(['pagebuilder.theme.active_theme' => $theme->alias]);
        $pageBuilder = new PHPageBuilder(config('pagebuilder'));
        $pageBuilder->handlePublicRequest();
        
        return $next($request);
    }
}
