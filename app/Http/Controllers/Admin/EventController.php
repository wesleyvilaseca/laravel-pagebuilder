<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index() {
        $data['events_'] = true;
        $data['toptitle'] = 'Eventos';
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => 'Eventos', 'active' => true];

        $data['events'] = Event::all();
        return view('admin.events.index', $data);
    }
}
