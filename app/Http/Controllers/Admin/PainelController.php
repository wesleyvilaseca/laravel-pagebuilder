<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use PHPageBuilder\Modules\Auth\Auth;

class PainelController extends Controller
{
    //
    public function index()
    {
        $data['home'] = true;
        return view('admin.admin', $data);
    }
}
