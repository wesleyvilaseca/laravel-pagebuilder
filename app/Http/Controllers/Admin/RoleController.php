<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function __construct(
        protected Role $repository
    )
    {
        $this->middleware(['can:roles']);
    }

    public function index()
    {
        $data['roles_'] = true;
        $data['title']  = 'Perfil de usuário';
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['list']       = $this->repository->all();

        return view('admin.roles.index', $data);
    }

    public function create()
    {
        $data['title']      = 'Novo perfil';
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' =>route('roles'), 'title' => 'Perfil de usuário'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['roles_']      = true;
        $data['action']     = route('role.save');

        return view('admin.roles.create', $data);
    }

    public function edit($id)
    {
        $role = $this->repository->find($id);
        if (!$role) {
            return redirect()->back();
        }

        $data['roles_']      = true;
        $data['title']      = 'Editar perfil ' . $role->title;
        $data['toptitle']   =  $data['title'];
        $data['roles']      = true;
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' =>route('roles'), 'title' => 'Perfil de usuário'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['role']       = $role;
        $data['action']     = route('role.update', $role->id);

        return view('admin.roles.edit', $data);
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
            return redirect()->route('roles')->with('success', 'Perfil criado com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('roles')->with('warning',  $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $role = $this->repository->find($id);
        if (!$role) {
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
            return redirect()->route('roles')->with('success', 'Perfil editado com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('roles')->with('warning',  $e->getMessage());
        }
    }

    public function delete($id)
    {
        $role = $this->repository->find($id);
        if (!$role) {
            return redirect()->back();
        }
     
        DB::beginTransaction();
        try {
            $role->delete();
            DB::commit();
            return redirect()->route('roles')->with('success', 'Perfil removido com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('roles')->with('warning',  $e->getMessage());
        }
    }
}
