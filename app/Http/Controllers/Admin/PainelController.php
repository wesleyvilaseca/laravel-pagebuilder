<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PainelController extends Controller
{
    //
    public function index()
    {
        $data['home'] = true;
        return view('admin.admin', $data);
    }
}
