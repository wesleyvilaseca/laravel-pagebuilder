<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index() {
        $data['templates_'] = true;
        $data['toptitle'] = 'Templates';
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => 'Templates', 'active' => true];
        $data['list'] = [];
        return view('admin.templates.index', $data);
    }
}
