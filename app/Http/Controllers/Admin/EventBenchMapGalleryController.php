<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventBenchMapGallery;
use App\Services\UploadFileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventBenchMapGalleryController extends Controller
{
 
    public function __construct(
        protected EventBenchMapGallery $repository,
        protected Event $eventRepository,
        protected UploadFileService $uploadFileService
        )
    {
        $this->middleware(['can:events']);
    }

    public function index($eventId) {
        if (!$eventId) {
            return redirect()->back();
        }

        $event = $this->eventRepository->find($eventId);
        if (!$event) {
            return redirect()->back();
        }

        $data['events_'] = true;
        $data['title'] = 'Mapa de bancadas do evento - ' . $event->name;
        $data['toptitle'] = $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('events'), 'title' => 'Eventos'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['event'] = $event;
        $data['gallery'] = $this->repository->where('event_id', $event->id)->get();

        return view('admin.events-benchmap-gallery.index', $data);
    }

    public function create($eventId) {
        if (!$eventId) {
            return redirect()->back();
        }

        $event = $this->eventRepository->find($eventId);
        if (!$event) {
            return redirect()->back();
        }

        $data['events_'] = true;
        $data['title'] = 'Novo mapa de bancada evento - ' . $event->name;
        $data['toptitle'] = $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('events'), 'title' => 'Eventos'];
        $data['breadcrumb'][] = ['route' => route('event.benchmap.gallery', $event->id), 'title' => 'Mapa de bancada do evento - ' . $event->name];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['event'] = $event;
        $data['action'] = route('event.benchmap.gallery.store', $event->id);

        return view('admin.events-benchmap-gallery.create', $data);
    }

    public function edit($eventId, $bannerId) {
        if (!$eventId || !$bannerId) {
            return redirect()->back();
        }

        $event = $this->eventRepository->find($eventId);

        if (!$event) {
            return redirect()->back();
        }

        $banner = $this->repository->find($bannerId);
        if (!$banner) {
            return redirect()->back();
        }

        $data['events_'] = true;
        $data['title'] = 'Editar mapa de bancada - ' . $banner->name;
        $data['toptitle'] = $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('events'), 'title' => 'Eventos'];
        $data['breadcrumb'][] = ['route' => route('event.benchmap.gallery', $event->id), 'title' => 'Mapa de bancada do evento - ' . $event->name];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['event'] = $event;
        $data['action'] = route('event.benchmap.gallery.update', [$event->id, $banner->id]);
        $data['image'] = $banner->uploads()->wherePivot('alias_category', $this->repository::FILE_CATEGORY_BENCHMAP)->first();
        $data['banner'] = $banner;

        return view('admin.events-benchmap-gallery.edit', $data);
    }


    public function show($eventId, $bannerId) {
        if (!$eventId || !$bannerId) {
            return redirect()->back();
        }

        $event = $this->eventRepository->find($eventId);

        if (!$event) {
            return redirect()->back();
        }

        $banner = $this->repository->find($bannerId);
        if (!$banner) {
            return redirect()->back();
        }

        $data['events_'] = true;
        $data['title'] = 'Mapa de bancada do envento - ' . $banner->name;
        $data['toptitle'] = $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('events'), 'title' => 'Eventos'];
        $data['breadcrumb'][] = ['route' => route('event.benchmap.gallery', $event->id), 'title' => 'Mapa de bancada do evento - ' . $event->name];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['event'] = $event;
        $data['action'] = route('event.benchmap.gallery.delete', [$event->id, $banner->id]);
        $data['image'] = $banner->uploads()->wherePivot('alias_category', $this->repository::FILE_CATEGORY_BENCHMAP)->first();
        $data['banner'] = $banner;
        $data['show'] = true;

        return view('admin.events-benchmap-gallery.show', $data);
    }

    public function store(Request $request, $eventId) {
        if (!$eventId) {
            return redirect()->back();
        }

        $event = $this->eventRepository->find($eventId);
        if (!$event) {
            return redirect()->back();
        }

        $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png|max:5048',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'link' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $banner = $this->repository->create([
                'event_id' => $event->id,
                'name' => $request->name,
                'description' => @$request->description,
                'order' => @$request->order ?? 0,
                'link' => @$request->link,
                'status' => 1
            ]);

            $storeBanner = $this->uploadFileService->upload(
                $request->file('image'),
                'events/event-' . $event->id . '/' . $this->repository::FILE_CATEGORY_BENCHMAP
            );

            $upload = $this->uploadFileService->store($storeBanner);

            $this->uploadFileService->storeUploadRelation([
                'system_upload_id' => $upload->id,
                'relation_id' => $banner->id,
                'alias_model_relation' => $this->repository::MODEL_ALIAS,
                'alias_category' => $this->repository::FILE_CATEGORY_BENCHMAP
            ]);
            DB::commit();
            return redirect()->route('event.benchmap.gallery', $event->id)->with('success', 'Mapa de bancada armazenado com sucesso.');
        } catch (Exception $e) {
            DB::rollback();
            if (isset($storeBanner)) {
                $this->uploadFileService->deleteFile($storeBanner['server_file']);
            }
            return redirect()->route('events')->with('warning',  $e->getMessage());
        }
    }

    public function update(Request $request, $eventId, $bannerId) {
        if (!$eventId || !$bannerId) {
            return redirect()->back();
        }

        $event = $this->eventRepository->find($eventId);
        if (!$event) {
            return redirect()->back();
        }

        $banner = $this->repository->find($bannerId);
        if(!$banner) {
            return redirect()->back();
        }

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'link' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $this->repository->where('id', $banner->id)->update([
                'name' => $request->name,
                'description' => @$request->description,
                'order' => @$request->order ?? 0,
                'link' => @$request->link,
                'status' => 1
            ]);

            DB::commit();
            return redirect()->route('event.benchmap.gallery', $event->id)->with('success', 'Mapa de bancada editado com sucesso.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('event.benchmap.gallery', $event->id)->with('warning',  $e->getMessage());
        }
    }

    public function delete($eventId, $bannerId) {
        if (!$eventId || !$bannerId) {
            return redirect()->back();
        }

        $event = $this->eventRepository->find($eventId);
        if (!$event) {
            return redirect()->back();
        }

        $banner = $this->repository->find($bannerId);
        if(!$banner) {
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $file = $banner->uploads;

            if (!empty($file[0])) {
                $this->uploadFileService->deleteFile(null, $file[0], true);
            }

            $banner->delete();
            DB::commit();
            return redirect()->route('event.benchmap.gallery', $event->id)->with('success', 'Mapa de bancada removido com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('event.benchmap.gallery', $event->id)->with('warning',  $e->getMessage());
        }
    }
}
