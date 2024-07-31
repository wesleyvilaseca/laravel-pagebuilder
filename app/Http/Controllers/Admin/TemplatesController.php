<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TemplateCreateRequest;
use App\Http\Requests\TemplateUpdateRequest;
use App\Models\Template;
use App\Models\Theme;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TemplatesController extends Controller
{
    public function index() {
        $data['templates_'] = true;
        $data['toptitle'] = 'Templates';
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => 'Templates', 'active' => true];
        $data['list'] = Template::paginate(15);
        return view('admin.templates.index', $data);
    }

    public function create() {
        $data['templates_'] = true;
        $data['toptitle'] = 'Templates';
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('templates'), 'title' => 'Templates'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => 'Novo template', 'active' => true];
        $data['themes'] = Theme::all();
        $data['action'] = route('templates.save');

        return view('admin.templates.create', $data);
    }

    public function edit($id = null) {
        if (!$id) {
            return redirect()->back();
        }

        $template = Template::find($id);
        if (!$template) {
            return redirect()->route('templates')->with('warning',  'Template inválido');
        }

        $data['templates_'] = true;
        $data['toptitle'] = 'Editar templates - ' . $template->name;
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('templates'), 'title' => 'Templates'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => 'Editar template - ' . $template->name, 'active' => true];
        $data['themes'] = Theme::all();
        $data['template'] = $template;
        $data['action'] = route('templates.update', $template->id);

        return view('admin.templates.edit', $data);
    }

    public function show($id = null) {
        if (!$id) {
            return redirect()->back();
        }

        $template = Template::find($id);
        if (!$template) {
            return redirect()->route('templates')->with('warning',  'Template inválido');
        }

        $data['templates_'] = true;
        $data['toptitle'] = 'Template - ' . $template->name;
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('templates'), 'title' => 'Templates'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => 'Apagar template - ' . $template->name, 'active' => true];
        $data['template'] = $template;
        $data['theme'] = Theme::find($template->theme_id);

        return view('admin.templates.show', $data);
    }

    public function store(TemplateCreateRequest $request) {

        DB::beginTransaction();
        try {
            $request->request->add(['url' => Str::slug($request->name)]);
            Template::create($request->all());
            DB::commit();

            return redirect()->route('templates')->with('success', 'Template criado com sucesso');
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return redirect()->route('templates')->with('warning',  $e->getMessage());
        }
    }

    public function update(TemplateUpdateRequest $request, $id) {
        DB::beginTransaction();
        try {
            $template = Template::find($id);
            if (!$template) {
                return redirect()->back();
            }

            $request->request->add(['url' => Str::slug($request->name)]);
            Template::where('id', $template->id)->update($request->except(['_token', '_method']));
            DB::commit();

            return redirect()->route('templates')->with('success', 'Template editado com sucesso');
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return redirect()->route('templates')->with('warning',  $e->getMessage());
        }
    }

    public function delete($id) {
        DB::beginTransaction();
        try {
            $template = Template::find($id);
            if (!$template) {
                return redirect()->back();
            }

            $template->delete();
            DB::commit();

            return redirect()->route('templates')->with('success', 'Template removido com sucesso com sucesso');
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return redirect()->route('templates')->with('warning',  $e->getMessage());
        }
    }
}
