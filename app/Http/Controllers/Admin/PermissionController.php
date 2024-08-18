<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    public function __construct(
        protected Permission $repository
    )
    {
        $this->middleware(['can:permissions']);
    }

    public function index()
    {
        $data['title']      = 'Permissões';
        $data['toptitle']   = $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['list']       = $this->repository->all();
        $data['action']     = route('permission.new');
        $data['permissions_']      = true;

        return view('admin.permissions.index', $data);
    }

    public function create()
    {
        $data['title']      = 'Nova permissão';
        $data['toptitle']   = $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('permissions'), 'title' => 'Permissões'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['permissions_']      = true;
        $data['action']     = route('permission.save');

        return view('admin.permissions.create', $data);
    }

    public function edit($id)
    {
        $permission = $this->repository->find($id);
        if (!$permission) {
            return redirect()->back();
        }

        $data['title']      = 'Editar permissão ' . $permission->title;
        $data['toptitle']   = $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('permissions'), 'title' => 'Permissões'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['permissions_']      = true;
        $data['permission']       = $permission;
        $data['action']     = route('permission.update', $permission->id);

        return view('admin.permissions.edit', $data);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name'         => 'required|string',
            'label'   => 'required|string',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with('errors', $validate->errors());
        }

        DB::beginTransaction();
        try {

            $this->repository->create([
                'name' => $request->name,
                'label' => $request->label
            ]);
           
            DB::commit();
            return redirect()->route('permissions')->with('success', 'success on create role');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('warning',  $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $permission = $this->repository->find($id);
        if (!$permission) {
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            'name'         => 'required|string',
            'label'   => 'required|string',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with('errors', $validate->errors());
        }

        DB::beginTransaction();
        try {

            $this->repository->where('id', $id)->update([
                'name'         => $request->name,
                'label'   => $request->label
            ]);
    
            DB::commit();
            return redirect()->route('permissions')->with('success', 'success on edit role');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('warning',  $e->getMessage());
        }
    }

    public function delete($id)
    {
        $post = $this->repository->find($id);
        if (!$post) {
            return redirect()->back();
        }

        DB::beginTransaction();
        try {

            $post->delete();
    
            DB::commit();
            return redirect()->route('permissions')->with('success', 'success on delete role');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('warning',  $e->getMessage());
        }
    }
}
