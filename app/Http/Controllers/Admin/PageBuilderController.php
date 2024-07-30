<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Theme;
use Illuminate\Http\Request;
use PHPageBuilder\PHPageBuilder;

class PageBuilderController extends Controller
{
    /**
     * Edit the given page with the page builder.
     *
     * @param int|null $pageId
     * @throws Throwable
     */
    public function build($pageId = null)
    {
        $route = $_GET['route'] ?? null;
        $action = $_GET['action'] ?? null;
        $pageId = is_numeric($pageId) ? $pageId : ($_GET['page'] ?? null);
        $pageRepository = new \PHPageBuilder\Repositories\PageRepository;
        $page = $pageRepository->findWithId($pageId);

        $phpPageBuilder = app()->make('phpPageBuilder');
        $pageBuilder = $phpPageBuilder->getPageBuilder();
        
        $customScripts = view("pagebuilder.scripts")->render();
        
        $pageBuilder->customScripts('head', $customScripts);
        $pageBuilder->handleRequest($route, $action, $page);
    }

    public function buildTemplate($templateId = null) {
        dd('aqui');
    }
}
