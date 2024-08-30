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
        //is event and has multiles uris
        if($this->event) {
            $page = Page::where(['route' => end($this->uri), 'event_id' => $this->event->id])->first();
            if(!$page) {
                $page = Page::where(['event_id' => $this->event->id, 'homepage' => Page::HOME_PAGE])->first();
                if (!$page) {
                    $page = Page::where(['event_id' => $this->event->id])->first();
                }
            }

            //if publisher page, send url
            if(end($this->uri) == Page::URL_PAGE_PUBLISHER) {
                $data['publisher'] = prev($this->uri);
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

    // public function index() {
    //     $page = Page::where(['event_id' => $this->event->id, 'homepage' => Page::HOME_PAGE])->first();
    //     if (!$page) {
    //         $page = Page::where(['event_id' => $this->event->id])->first();
    //     }

    //     $data['event'] = $this->event->url;
    //     $data['html'] = $this->htmlPage($page);

    //     return view('pagebuilder.base-view', $data);
    // }

    // public function domain(string $domain = '') {
    //     if ($this->event) {
            
    //     }
    //     $event = Event::where('url', $domain)->first();
    //     if(!$event) {
    //         $event = Event::where('principal', Event::PRINCIPAL_EVENT)->first();
    //         $page = Page::where(['route' => $domain, 'event_id' => $event->id,])->first();
    //         if (!$page) {
    //             return redirect()->route('notfound');
    //         }
    //     }
        
    //     $data['event'] = $this->event->url ?? '' ;
    //     $data['principal'] = $this->event->principal ?? false;
    //     $data['html'] = $this->htmlPage($page);

    //     return view('pagebuilder.base-view', $data);
    // }

    /**
     * Show the website page that corresponds with the current URI.
     */
    // public function uri(string $event = '', string $uri = '')
    // {
    //     $event = Event::where('url', $event)->first();
    //     if(!$event && $uri) {
    //         //has only uri, normaly is access on page create event
    //         $page = Page::where('route', $uri)->first();
    //     }else if ($event && $uri) {
    //         $page = Page::where(['event_id' => $event->id, 'route' => $uri])->first();
    //     } else if ($event && !$uri) {
    //         //access the especific event route
    //         $page = Page::where(['event_id' => $event->id, 'homepage' => Page::HOME_PAGE])->first();

    //         //if not home page, show any page
    //         if (!$page) {
    //             $page = Page::where(['event_id' => $event->id])->first();
    //         }
    //     } else {
    //         //render a single template page
    //         $page = Page::where(['route' => $uri])->first();
    //     }

    //     if (!$page) {
    //         return redirect()->route('notfound');
    //     }

    //     $data['event'] = $this->event->url ?? $event->url ?? '' ;
    //     $data['principal'] = $this->event->principal ?? false;
    //     $data['html'] = $this->htmlPage($page);

    //     return view('pagebuilder.base-view', $data);
    // }

    // public function domainUri(string $event = '', string $uri = '')
    // {
    //     $isTemplate = Template::where('url', $event)->first() ?? false;
    //     if($isTemplate) {
    //         $page = Page::where(['route' => $uri])->first();
    //         if (!$page) {
    //             return redirect()->route('notfound');
    //         }
    //     } else {
    //         $event = Event::where('url', $event)->first();
    //         if(!$event) {
    //             return redirect()->route('notfound');
    //         }

    //         $page = Page::where(['event_id' => $event->id, 'route' => $uri])->first();
    //         if(!$page) {
    //             $page = Page::where(['event_id' => $event->id, 'homepage' => Page::HOME_PAGE])->first();
    //         }
    
    //         if (!$page) {
    //             $page = Page::where(['event_id' => $event->id])->first();
    //         }
    //     }
      
    //     $data['event'] = $this->event->url ?? $event->url ?? '' ;
    //     $data['principal'] = $this->event->principal ?? false;
    //     $data['html'] = $this->htmlPage($page);

    //     return view('pagebuilder.base-view', $data);
    // }
}
 