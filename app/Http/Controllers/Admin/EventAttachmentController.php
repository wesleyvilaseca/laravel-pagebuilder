<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventAttachment;
use App\Services\UploadFileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventAttachmentController extends Controller
{
    public function __construct(
        protected EventAttachment $repository,
        protected Event $eventRepository,
        protected UploadFileService $uploadFileService
    )
    {
        
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
        $data['title'] = 'Anexos do evento - ' . $event->name;
        $data['toptitle'] = $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('events'), 'title' => 'Eventos'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['event'] = $event;
        $data['gallery'] = $this->repository->where('event_id', $event->id)->get();

        return view('admin.events-attachments.index', $data);
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
        $data['title'] = 'Novo anexo evento - ' . $event->name;
        $data['toptitle'] = $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('events'), 'title' => 'Eventos'];
        $data['breadcrumb'][] = ['route' => route('event.attachments', $event->id), 'title' => 'Anexos do evento - ' . $event->name];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['event'] = $event;
        $data['action'] = route('event.attachment.store', $event->id);

        return view('admin.events-attachments.create', $data);
    }

    public function edit($eventId, $attachmentId) {
        if (!$eventId || !$attachmentId) {
            return redirect()->back();
        }

        $event = $this->eventRepository->find($eventId);

        if (!$event) {
            return redirect()->back();
        }

        $attachment = $this->repository->find($attachmentId);
        if (!$attachment) {
            return redirect()->back();
        }

        $data['events_'] = true;
        $data['title'] = 'Editar anexo - ' . $attachment->name;
        $data['toptitle'] = $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('events'), 'title' => 'Eventos'];
        $data['breadcrumb'][] = ['route' => route('event.attachments', $event->id), 'title' => 'Anexos do evento - ' . $event->name];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['event'] = $event;
        $data['action'] = route('event.attachment.update', [$event->id, $attachment->id]);
        $data['attachmentFile'] = $attachment->uploads()->wherePivot('alias_category', $this->repository::FILE_CATEGORY_ATTACHMENT)->first();
        $data['attachment'] = $attachment;

        return view('admin.events-attachments.edit', $data);
    }

    public function show($eventId, $attachmentId) {
        if (!$eventId || !$attachmentId) {
            return redirect()->back();
        }

        $event = $this->eventRepository->find($eventId);

        if (!$event) {
            return redirect()->back();
        }

        $attachment = $this->repository->find($attachmentId);
        if (!$attachment) {
            return redirect()->back();
        }

        $data['events_'] = true;
        $data['title'] = 'Anexo do envento - ' . $attachment->name;
        $data['toptitle'] = $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('events'), 'title' => 'Eventos'];
        $data['breadcrumb'][] = ['route' => route('event.attachments', $event->id), 'title' => 'Anexos do evento - ' . $event->name];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['event'] = $event;
        $data['action'] = route('event.attachment.delete', [$event->id, $attachment->id]);
        $data['attachmentFile'] = $attachment->uploads()->wherePivot('alias_category', $this->repository::FILE_CATEGORY_ATTACHMENT)->first();
        $data['attachment'] = $attachment;
        $data['show'] = true;

        return view('admin.events-attachments.show', $data);
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
            'attachment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5048',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'link' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $attachment = $this->repository->create([
                'event_id' => $event->id,
                'name' => $request->name,
                'description' => @$request->description,
                'order' => @$request->order ?? 0,
                'status' => 1
            ]);

            $storeAttachment = $this->uploadFileService->upload(
                $request->file('attachment'),
                'events/event-' . $event->id . '/' . $this->repository::FILE_CATEGORY_ATTACHMENT
            );

            $upload = $this->uploadFileService->store($storeAttachment);

            $this->uploadFileService->storeUploadRelation([
                'system_upload_id' => $upload->id,
                'relation_id' => $attachment->id,
                'alias_model_relation' => $this->repository::MODEL_ALIAS,
                'alias_category' => $this->repository::FILE_CATEGORY_ATTACHMENT
            ]);
            DB::commit();
            return redirect()->route('event.attachments', $event->id)->with('success', 'Anexo armazenado com sucesso.');
        } catch (Exception $e) {
            DB::rollback();
            if (isset($storeBanner)) {
                $this->uploadFileService->deleteFile($storeBanner['server_file']);
            }
            return redirect()->route('event.attachments', $event->id)->with('warning',  $e->getMessage());
        }
    }

    public function update(Request $request, $eventId, $attachmentId) {
        if (!$eventId || !$attachmentId) {
            return redirect()->back();
        }

        $event = $this->eventRepository->find($eventId);
        if (!$event) {
            return redirect()->back();
        }

        $attachment = $this->repository->find($attachmentId);
        if(!$attachment) {
            return redirect()->back();
        }

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        DB::beginTransaction();
        try {
            $this->repository->where('id', $attachment->id)->update([
                'name' => $request->name,
                'description' => @$request->description,
                'order' => @$request->order ?? 0,
                'status' => 1
            ]);

            DB::commit();
            return redirect()->route('event.attachments', $event->id)->with('success', 'Anexo editado com sucesso.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('event.attachments', $event->id)->with('warning',  $e->getMessage());
        }
    }

    public function delete($eventId, $attachmentId) {
        if (!$eventId || !$attachmentId) {
            return redirect()->back();
        }

        $event = $this->eventRepository->find($eventId);
        if (!$event) {
            return redirect()->back();
        }

        $attachment = $this->repository->find($attachmentId);
        if(!$attachment) {
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $file = $attachment->uploads;

            if (!empty($file[0])) {
                $this->uploadFileService->deleteFile(null, $file[0], true);
            }

            $attachment->delete();
            DB::commit();
            return redirect()->route('event.attachments', $event->id)->with('success', 'Mapa de bancada removido com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('event.attachments', $event->id)->with('warning',  $e->getMessage());
        }
    }
}
