<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Template;
use App\Models\TemplatePage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    public function index($url = null) {
        if (!$url) {
            return redirect()->back();
        }

        $template = Template::where('url', $url)->first();
        if (!$template) {
            return redirect()->route('templates')->with('warning',  'Template inválido');
        }

        $data['templates_'] = true;
        $data['toptitle'] = 'Paginas do template';
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('templates'), 'title' => 'Templates'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => 'Paginas template - ' . $template->name, 'active' => true];
        $data['pages'] = $template->pages;
        $data['template']       = $template;

        return view('admin.template.index', $data);
    }

    public function create($template_id = null)
    {
        $template = Template::where(['id' => $template_id])->first();

        if (!$template) {
            return redirect()->back();
        }

        $data['title']          = 'Nova página template ' . $template->name;
        $data['toptitle']       = $data['title'];
        $data['breadcrumb'][]   = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][]   = ['route' => route('templates'), 'title' => 'Templates'];
        $data['breadcrumb'][]   = ['route' => route('template.pages', $template->url), 'title' => 'Paginas template - ' . $template->name];
        $data['breadcrumb'][]   = ['route' => '#', 'title' => $data['title'] , 'active' => true];
        $data['templates_']     = true;
        $data['template']       = $template->pages;
        $data['action']         = route('template.pages.store', $template->id);

        return view('admin.template.create-page', $data);
    }

    public function store(Request $request, $templateId)
    {
        DB::beginTransaction();
        try {
            $template = Template::where(['id' => $templateId])->first();
            if (!$template) {
                return redirect()->back();
            }

            $data = $request->except(['_token']);
            $data['route'] = Str::slug($request->name);
            $data['template_id'] = $template->id;

            $hashomepage = $template->pages->where(['homepage' => Page::HOME_PAGE])->first();
            if ($hashomepage && $request->homepage == Page::HOME_PAGE) {
                $result = $hashomepage->update(['homepage' => 0]);
                if (!$result) {
                    return redirect()->back()->with('error', 'erro ao atualiza a homepage');
                }
            }
           
            $page = Page::create($data);

            TemplatePage::create(['template_id' => $template->id, 'page_id' => $page->id]);

            DB::commit();
            return redirect()->route('template.pages', $template->url)->with('success', 'Página criada com sucesso');
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return redirect()->route('template.pages', $template->url)->with('warning',  $e->getMessage());
        }
    }
}
