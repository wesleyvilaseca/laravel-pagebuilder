<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventCreateRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Models\Event;
use App\Models\EventPublisher;
use App\Models\Page;
use App\Models\Template;
use App\Models\Theme;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:events']);
    }

    public function index() {
        $data['events_'] = true;
        $data['title']  = 'Eventos';
        $data['toptitle'] = 'Eventos';
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => 'Eventos', 'active' => true];

        $data['events'] = Event::all();
        return view('admin.events.index', $data);
    }

    public function create() {
        $data['events_'] = true;
        $data['title'] = 'Criar novo evento';
        $data['toptitle'] = $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('events') , 'title' => 'Eventos'];
        $data['breadcrumb'][] = ['route' => '#' , 'title' => $data['title'], 'active' => true];
        $data['themes'] = Theme::all();
        $data['templates'] = Template::all();
        $data['action'] = route('event.store');

        return view('admin.events.create-event', $data);
    }

    public function edit($id) {
        if (!$id) {
            return redirect()->back();
        }

        $event = Event::find($id);
        if (!$event) {
            return redirect()->back();
        }

        $data['events_'] = true;
        $data['title'] = 'Edidar evento - ' . $event->name;
        $data['toptitle'] = $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('events') , 'title' => 'Eventos'];
        $data['breadcrumb'][] = ['route' => '#' , 'title' => $data['title'], 'active' => true];
        $data['themes'] = Theme::all();
        $data['templates'] = Template::all();
        $data['action'] = route('event.update', $event->id);
        $data['event'] = $event;

        return view('admin.events.edit-event', $data);
    }


    public function store(EventCreateRequest $request) {
        DB::beginTransaction();
        try {
            $address = (object) [
                'address' => @$request->address,
                'zip_code' => @$request->zip_code,
                'state' => @$request->state,
                'district' => @$request->district,
                'city' => @$request->city,
                'number' => @$request->number,
                'latitude' => @$request->latitude,
                'longitude' => @$request->longitude,
                'instruction' => @$request->instruction,
            ];

            if ($request->select_template == 1) {
                $template = Template::find($request->template_id);
                if (!$template) {
                    return redirect()->route('events')->with('error', 'Template inválido.');
                }

                $event = Event::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'principal' => $request->principal,
                    'status' => $request->status,
                    'url' => Str::slug($request->name),
                    'theme_id' => $template->theme_id,
                    'data' => json_encode((object)[
                        'address' => $address
                    ])
                ]);

                $pages = $template->pages;
                foreach ($pages as $page) {
                    Page::create([
                        'name' => $page->name,
                        'layout' => $page->layout,
                        'data' => $page->data,
                        'event_id' => $event->id,
                        'homepage' => $page->homepage,
                        'route' => $page->route
                    ]);
                }
            }

            if ($request->select_template == 0) {
                $theme = Theme::find($request->theme_id);
                if (!$theme) {
                    return redirect()->route('events')->with('error', 'Tema inválido.');
                }

                $event = Event::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'principal' => $request->principal,
                    'status' => $request->status,
                    'url' => Str::slug($request->name),
                    'theme_id' => $request->theme_id,
                    'data' => json_encode((object)[
                        'address' => $address
                    ])
                ]);
            }

            if($request->principal == Event::PRINCIPAL_EVENT && $request->status == Event::EVENT_ACTIVE) {
                $principalEvent = Event::where([
                    'principal' => Event::PRINCIPAL_EVENT,
                    'status' => Event::EVENT_ACTIVE
                ])
                ->first();

                if ($principalEvent && $principalEvent->id != $event->id) {
                    $principalEvent->update(['principal' => Event::NOT_PRINCIPAL_ENVENT]);
                }
            }

            $existPrincipalEvent = Event::where('principal', Event::PRINCIPAL_EVENT)->first();
            if (!$existPrincipalEvent) {
                $event->update([
                    'principal' => Event::PRINCIPAL_EVENT
                ]);
            }

            DB::commit();
            return redirect()->route('events')->with('success', 'Evento criado com sucesso');
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return redirect()->route('events')->with('warning',  $e->getMessage());
        }
    }

    public function update(EventUpdateRequest $request, $id) {
        DB::beginTransaction();
        try {
            $event = Event::find($id); 
            if (!$event) {
                return redirect()->route('events')->with('error', 'Evento inválido.');
            }

            if ($request->status == Event::EVENT_INACTIVE && $event->principal == Event::PRINCIPAL_EVENT) {
                return redirect()->route('events')->with('error', 'Não é possível desativar um evento principal.');
            }

            if($request->principal == Event::PRINCIPAL_EVENT && $request->status == Event::EVENT_ACTIVE) {
                $principalEvent = Event::where([
                    'principal' => Event::PRINCIPAL_EVENT,
                    'status' => Event::EVENT_ACTIVE
                ])
                ->first();

                if ($principalEvent && $principalEvent->id != $event->id) {
                    $principalEvent->update(['principal' => Event::NOT_PRINCIPAL_ENVENT]);
                }
            }

            $address = (object) [
                'address' => @$request->address,
                'zip_code' => @$request->zip_code,
                'state' => @$request->state,
                'district' => @$request->district,
                'city' => @$request->city,
                'number' => @$request->number,
                'latitude' => @$request->latitude,
                'longitude' => @$request->longitude,
                'instruction' => @$request->instruction,
            ];

            $event->update([
                'name' => $request->name,
                'description' => $request->description,
                'principal' => $request->principal,
                'status' => $request->status,
                'url' => Str::slug($request->name),
                'theme_id' => $event->theme_id,
                'data' => json_encode((object)[
                    'address' => $address
                ])
            ]);

            DB::commit();

            return redirect()->route('events')->with('success', 'Evento editado com sucesso');
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return redirect()->route('events')->with('warning',  $e->getMessage());
        }
    }

    public function delete($id) {
        DB::beginTransaction();
        try {
            $event = Event::find($id); 
            if (!$event) {
                return redirect()->route('events')->with('error', 'Evento inválido.');
            }

            if ($event->principal == Event::PRINCIPAL_EVENT) {
                return redirect()->route('events')->with('error', 'Não é possível apagar um evento principal.');
            }

            Page::where('event_id', $event->id)->delete();
            EventPublisher::where('event_id')->delete();
            $event->delete();

            DB::commit();

            return redirect()->route('events')->with('success', 'Evento removido com sucesso com sucesso');
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return redirect()->route('events')->with('warning',  $e->getMessage());
        } 
    }
}
