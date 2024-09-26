<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Supports\Helper\VarnishCacheHelper;
use Exception;
use Illuminate\Http\Request;
use PHPageBuilder\Modules\Auth\Auth;

class PainelController extends Controller
{
    //
    public function index()
    {
        $data['home'] = true;
        $data['title'] = 'Painel administrativo';
        $data['toptitle'] = $data['title'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => 'Dashboard' , 'active' => true];
        return view('admin.admin', $data);
    }

    public function purgeVarnishCache() {
        try {
            (new VarnishCacheHelper)->purgeCache();
            return redirect()->back()->with('success', 'Cache apagado com sucesso.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
