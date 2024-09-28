<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Page;
use App\Models\Template;
use Illuminate\Http\Request;
use PHPageBuilder\Modules\GrapesJS\Block\BaseModel;
use PHPageBuilder\Modules\GrapesJS\PageRenderer;
use PHPageBuilder\Repositories\PageRepository;
use PHPageBuilder\Theme;

class ControllersWebsiteController extends Controller
{
    protected $event;
    protected $uri;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->event = $request->session()->get('event') ?? null;
            $url = explode('/', str_replace([env('APP_URL'), 'http://', 'https://'], "", request()->url()));
            $url = array_values(array_filter($url, function($value) {  return $value !== ""; }));
            $this->uri = $url;

            return $next($request);
        });
    }

    public function uri() {
        $publisherPage = in_array(Page::URL_PAGE_PUBLISHER, $this->uri);
        if($publisherPage) {
            $data['publisher'] = end($this->uri);
        }
        //is event and has multiles uris
        if($this->event) {
            $page = Page::where(['route' => !$publisherPage ? end($this->uri) : Page::URL_PAGE_PUBLISHER, 'event_id' => $this->event->id])->first();
            if(!$page) {
                $page = Page::where(['event_id' => $this->event->id, 'homepage' => Page::HOME_PAGE])->first();
                if (!$page) {
                    $page = Page::where(['event_id' => $this->event->id])->first();
                }
            }
            
            $data['uri'] = $this->uri;
            $data['template'] = false;
            $data['event'] = $this->event->url ?? '' ;
            $data['principal'] = $this->event->principal ?? false;
            $data['html'] = $this->htmlPage($page);
            return view('pagebuilder.base-view', $data);
        }

        //is a template page
        $template = Template::where('url', $this->uri[0])->first();
        if(!$this->event && $template) {
            if(sizeof($this->uri) > 1) {
                $page = $template->pages->where('route', end($this->uri))->first();
                $data['uri'] = $this->uri;
                $data['template'] = true;
                $data['event'] = '';
                $data['principal'] = false;
                $data['html'] = $this->htmlPage($page);
                return view('pagebuilder.base-view', $data);
            }
        } else {
            return abort(404);
        }
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
}
 