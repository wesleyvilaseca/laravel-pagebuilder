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
    /**
     * Show the website page that corresponds with the current URI.
     */
    public function uri(Request $request, $event = '', $uri = '')
    {
        $event = Event::where('url', $event)->first();
        if ($event && !$uri) {
            $page = Page::where(['event_id' => $event->id, 'homepage' => Page::HOME_PAGE])->first();

            if (!$page) {
                $page = Page::where(['event_id' => $event->id])->first();
            }
        } else {
            $page = Page::where(['event_id' => $event->id, 'route' => $uri])->first();
        }

        if (!$page) {
            return redirect()->route('notfound');
        }

        $theme = new Theme(config('pagebuilder.theme'), config('pagebuilder.theme.active_theme'));
        $page = (new PageRepository)->findWithId($page->id);
        $pageRenderer = new PageRenderer($theme, $page);
        $html = $pageRenderer->render();
        return $html;
    }

    public function editora(Request $request, $event = '') {
        echo $this->uri($request, $event);
    }

    public function notfound(Request $request)
    {
        $data['title'] = 'Page not found';
        return view('common.404.pagenotfound', $data);
    }
}
