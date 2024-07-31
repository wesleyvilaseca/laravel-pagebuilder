<?php

namespace App\Http\Middleware;

use App\Models\Event;
use App\Models\Template;
use App\Models\Theme;
use App\Supports\Helper\Utils;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use PHPageBuilder\PHPageBuilder;

class ActiveThemeTemplateEventMiddleware
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
        if(@$params['template'])  {
            $templateUrl = $params['template'];
        }

        if (@$_GET['template']) {
            $templateUrl = $_GET['template'];
        }
        
        if(!$templateUrl) {
            return abort(404);
        }

        $template = Template::where('url', $templateUrl)->first();
        if (!$template) {
            return abort(404);
        }

        $activeThemeTemplate = Theme::find($template->theme_id);

        /**
         * caso não seja a página do builder
         */
        if(@$params['template'])  {
            $_SESSION[$templateUrl] = $activeThemeTemplate->alias;
            return $next($request);
        }

        $themeSession = @$_SESSION[$templateUrl];
        if ($activeThemeTemplate->alias == config('pagebuilder.theme.active_theme') || $themeSession == $activeThemeTemplate->alias) {
            return $next($request);
        }

        $_SESSION[$templateUrl] = $activeThemeTemplate->alias;
        
        return Redirect::back();

    }
}
