<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionRoleController extends Controller
{
    public function __construct(
        protected Role $roleRepository,
        protected Permission $permissionRepository,
        protected PermissionRole $permissionRoleRepository
    )
    {
        $this->middleware(['can:permission_role']);
    }

    public function index($roleId)
    {
        $role = $this->roleRepository->find($roleId);
        if (!$roleId) return redirect()->back();

        $data['roles_'] = true;
        $data['permissions'] = $this->permissionRepository->all();
        $data['permissions_role'] = $this->permissionRoleRepository->where('role_id', $role->id)->get();
        $data['title'] = 'Permissões do perfil - ' . $role->name;
        $data['toptitle'] = $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('roles'), 'title' => 'Perfil de usuários'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['action'] = route('role.permissions.sync', $role->id);

        return view('admin.permissions_roles.index', $data);
    }

    public function sync($id, Request $request)
    {
        $role = $this->roleRepository->find($id);
        if (!$role) return redirect()->back();

        DB::beginTransaction();
        try {
           if ($request->permission) {
                $this->permissionRoleRepository->where('role_id', $role->id)->delete();

                $data = [];
                foreach ($request->permission as $permission) {
                    $data[] = ['permission_id' => $permission, 'role_id' => $role->id];
                }

                $this->permissionRoleRepository->insert($data);
            }
           
            DB::commit();
            return redirect()->route('roles')->with('success', 'Success on operation');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('roles')->with('warning',  $e->getMessage());
        }
    }
}
