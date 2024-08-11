<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Publisher;
use Illuminate\Http\Request;

class EventPublisherController extends Controller
{
    public function __construct(
        protected Event $eventRepository,
        protected Publisher $publisherRepository
    )
    {
    }

    public function index(int $eventId)
    {
        $event = $this->eventRepository->find($eventId);
        if (!$event) {
            return redirect()->back();
        }

        $data['title'] = 'Editoras do evento ' . $event->name;
        $data['toptitle'] = $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('events') , 'title' => 'Eventos'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'] , 'active' => true];
        $data['events_'] = true;
        $data['event'] = $event;
        $data['publishers'] = $event->publishers;

        return view('admin.event-publishers.index', $data);
    }

    public function available(int $eventId) {
        $event = $this->eventRepository->find($eventId);
        if (!$event) {
            return redirect()->back();
        }

        $data['title'] = 'Adicionar editoras para o evento ' . $event->name;
        $data['toptitle'] = $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('events') , 'title' => 'Eventos'];
        $data['breadcrumb'][] = ['route' => route('event.publishers', $event->id) , 'title' => 'Editoras do evento - ' . $event->name];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'] , 'active' => true];
        $data['events_'] = true;
        $data['event'] = $event;
        $data['publishers'] = $event->publishersAvailable();

        return view('admin.event-publishers.available', $data);
    }

    public function attachPublisherEvent(Request $request, $eventId)
    {
        $event = $this->eventRepository->find($eventId);
        if (!$event) {
            return redirect()->back()->with('error', 'Operação não autorizada');
        }

        if (!$request->publishers || count($request->publishers) == 0) 
            return redirect()->back()->with('warning', 'Precisa escolher pelo menos uma editora');
        
        $event->publishers()->attach($request->publishers);

        return redirect()->route('event.publishers', $event->id);
    }

    public function detachEventPublisher($enventId, $publisherId)
    {
        $event = $this->eventRepository->find($enventId);
        $publisher = $this->publisherRepository->find($publisherId);

        if (!$event || !$publisher) {
            return redirect()->back()->with('error', 'Operação não autorizada');
        }
            
        
        $event->publishers()->detach($publisher);

        return redirect()->route('event.publishers', $event->id);
    }
}
