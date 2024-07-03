<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class EventManegerController extends Controller
{
    public function index($url) {
        if (!$url) {
            return Redirect::back();
        }

        $event = Event::where('url', $url)->first();
        if (!$event) {
            return Redirect::back();
        }

        $data['events_'] = true;
        $data['toptitle'] = 'Eventos';
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('events'), 'title' => 'Eventos'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => 'Evento - ' . $event->name, 'active' => true];
        $data['event'] = $event;
        $data['pages'] = Page::where('event_id', $event->id)->get();
        return view('admin.event.pages', $data);
    }

    public function create($event_id)
    {
        $event = Event::where(['id' => $event_id])->first();

        if (!$event) {
            return redirect()->back()->with('error', 'operação não autorizada');
        }

        $data['title']          = 'Paginas do evento ' . $event->title;
        $data['toptitle']       = 'Paginas do evento ' . $event->title;
        $data['breadcrumb'][]   = ['route' => route('painel'), 'title' => 'Painel de controle'];
        $data['breadcrumb'][]   = ['route' => route('events'), 'title' => 'Eventos'];
        $data['breadcrumb'][]   = ['route' => route('event.pages', $event->url), 'title' => 'Páginas do evento ' . $event->title];
        $data['breadcrumb'][]   = ['route' => '#', 'title' => 'Nova página', 'active' => true];
        $data['event']          = $event;
        $data['events_']        = true;
        $data['action']         = route('pages.store', $event->id);

        return view('admin.event.create-page', $data);
    }

    public function edit($event_id, $pageid)
    {
        $event = Event::where(['id' => $event_id])->first();
        $page = Page::find($pageid);

        if (!$event || !$page) {
            return redirect()->back()->with('error', 'operação não autorizada');
        }

        $data['title']          = 'Evento ' . $event->name;
        $data['toptitle']       = 'Evento ' . $event->name;
        $data['breadcrumb'][]   = ['route' => route('painel'), 'title' => 'Painel de controle'];
        $data['breadcrumb'][]   = ['route' => route('events'), 'title' => 'Eventos'];
        $data['breadcrumb'][]   = ['route' => route('event.pages', $event->url), 'title' => 'Páginas do evento ' . $event->name];
        $data['breadcrumb'][]   = ['route' => '#', 'title' => 'Editar página ' . $page->name, 'active' => true];
        $data['event']        = $event;
        $data['page']           = $page;
        $data['events_']            = true;

        $data['action']         = route('pages.update', [$event->id, $page->id]);

        return view('admin.event.create-page', $data);
    }

    public function store(Request $request, $event_id)
    {
        $event = Event::where(['id' => $event_id])->first();
        if (!$event) {
            return redirect()->back()->with('error', 'operação não autorizada');
        }

        $data = $request->except(['_token']);
        $data['user_id'] = auth()->user()->id;
        $data['route'] =  Str::slug($request->name);
        $data['event_id'] = $event->id;

        $hashomepage = Page::where(['event_id' => $event->id, 'homepage' => Page::HOME_PAGE])->first();
        if ($hashomepage and $request->homepage == Page::HOME_PAGE) {
            $result = $hashomepage->update(['homepage' => 0]);
            if (!$result) {
                return redirect()->back()->with('error', 'erro ao atualiza a homepage');
            }
        }

        $result = Page::create($data);

        if (!$result) {
            return redirect()->back()->with('error', 'erro na operação');
        }

        return redirect()->route('event.pages', $event->url)->with('success', 'Pagina criado com sucesso');
    }

    public function update(Request $request, $event_id, $pageid)
    {
        $event = Event::where(['id' => $event_id])->first();
        $page = Page::find($pageid);

        if (!$event || !$page) {
            return redirect()->back()->with('error', 'operação não autorizada');
        }

        $data = $request->except(['_token']);
        $data['route'] =  Str::slug($request->name);

        $hashomepage = Page::where(['event_id' => $event->id, 'homepage' => 1])->first();
        if ($hashomepage and $request->homepage == Page::HOME_PAGE) {
            $result = $hashomepage->update(['homepage' => 0]);
            if (!$result) return redirect()->back()->with('error', 'erro ao atualiza a homepage');
        }

        $result = $page->update($data);

        if (!$result) {
            return redirect()->back()->with('error', 'erro na operação');
        }

        return redirect()->route('event.pages', $event->url)->with('success', 'Pagina editada com sucesso');
    }

    public function delete($event_id, $pageid)
    {
        $event = Event::where(['id' => $event_id])->first();
        $page = Page::find($pageid);

        if (!$event || !$page) {
            return redirect()->back()->with('error', 'operação não autorizada');
        }

        $result = $page->delete();

        if (!$result) {
            return redirect()->back()->with('error', 'erro na operação');
        }

        return redirect()->route('event.pages', $event->url)->with('success', 'Página removido com sucesso');
    }
}
