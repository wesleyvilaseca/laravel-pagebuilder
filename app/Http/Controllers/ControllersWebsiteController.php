<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Page;
use Illuminate\Http\Request;
use PHPageBuilder\Modules\GrapesJS\Block\BaseModel;
use PHPageBuilder\Modules\GrapesJS\PageRenderer;
use PHPageBuilder\Repositories\PageRepository;
use PHPageBuilder\Theme;

class ControllersWebsiteController extends Controller
{
    protected $event;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->event = $request->session()->get('event');
            return $next($request);
        });
    }

    public function index() {
        $page = Page::where(['event_id' => $this->event->id, 'homepage' => Page::HOME_PAGE])->first();
        if (!$page) {
            $page = Page::where(['event_id' => $this->event->id])->first();
        }

        $data['event'] = $this->event->url;
        $data['html'] = $this->htmlPage($page);

        return view('pagebuilder.base-view', $data);
    }

    /**
     * Show the website page that corresponds with the current URI.
     */
    public function uri(string $event = '', string $uri = '')
    {
        $event = Event::where('url', $event)->first();
        if(!$event && $uri) {
            //has only uri, normaly is access on page create event
            $page = Page::where('route', $uri)->first();
        }else if ($event && $uri) {
            $page = Page::where(['event_id' => $event->id, 'route' => $uri])->first();
        } else if ($event && !$uri) {
            //access the especific event route
            $page = Page::where(['event_id' => $event->id, 'homepage' => Page::HOME_PAGE])->first();

            //if not home page, show any page
            if (!$page) {
                $page = Page::where(['event_id' => $event->id])->first();
            }
        } else {
            //render a single template page
            $page = Page::where(['route' => $uri])->first();
        }

        if (!$page) {
            return redirect()->route('notfound');
        }

        $data['event'] = $this->event->url ?? $event->url ?? '' ;
        $data['html'] = $this->htmlPage($page);

        return view('pagebuilder.base-view', $data);
    }

    private function htmlPage(Page $page) {
        $theme = new Theme(config('pagebuilder.theme'), config('pagebuilder.theme.active_theme'));
        $page = (new PageRepository)->findWithId($page->id);
        $pageRenderer = new PageRenderer($theme, $page);
        $html = $pageRenderer->render();
        return $html;
    }

    public function notfound(Request $request)
    {
        $data['title'] = 'Page not found';
        return view('common.404.pagenotfound', $data);
    }

    public function editora(Request $request, $event = '') {
        echo $this->uri($request, $event);
    }
}
 