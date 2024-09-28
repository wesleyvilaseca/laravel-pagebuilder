<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct(
        protected User $repository,
        protected Role $roleRepository,
        protected RoleUser $roleUserRepository
    )
    {
        $this->middleware(['can:users']);
    }

    public function index()
    {
        if(Auth::user()->isAdmin()) {
            $list = $this->repository->all();
        } else {
            $list = $this->repository->where('super_admin', 'N')->get();
        }
        
        $data['users_'] = true;
        $data['title']  = 'Usuários';
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['list']       = $list;
        $data['users_']     = true;

        return view('admin.users.index', $data);
    }

    public function create()
    {
        $data['title']      = 'Novo usuário';
        $data['toptitle']   = $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('users'), 'title' => 'Usuários'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['roles_list'] = $this->roleRepository->all();
        $data['users_']      = true;
        $data['action']     = route('user.save');

        return view('admin.users.create', $data);
    }

    public function edit($id)
    {
        $user = $this->repository->find($id);
        if (!$user) {
            return redirect()->back();
        }

        $data['title']      = 'Editar usuário ' . $user->title;
        $data['toptitle']   = $data['title'];
        $data['roles_list'] =$this->roleRepository->all();
        $data['users_']     = true;
        $data['user']       = $user;
        $data['role_user'] = @$user->roles->first()->id;
        $data['action']     = route('user.update', $user->id);

        return view('admin.users.edit', $data);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email'   => 'required|email|unique:users,email',
            'password'   => 'required|string|min:6',
            'role_id'   => 'required|integer'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with('errors', $validate->errors());
        }

        DB::beginTransaction();
        try {
          
            $result = $this->repository->create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $this->roleUserRepository->create(['role_id' => $request->role_id, 'user_id' => $result->id]);

            DB::commit();
            return redirect()->route('users')->with('success', 'Usuário criado com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('users')->with('warning',  $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $user = user::find($id);
        if (!$user) {
            return redirect()->back();
        }

        if($user->isAdmin() && !Auth::user()->isAdmin()) {
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            'first_name'    => 'required|string',
            'last_name' => 'required|string',
            'email'   => 'required|email|unique:users,email,' . $id,
            'password'   => 'nullable|string|min:6',
            'role_id'   => 'required|integer'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with('errors', $validate->errors());
        }

        DB::beginTransaction();
        try {
            $data = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email'        => $request->email
            ];

            $this->repository->where('id', $id)->update($data);

            if(!$user->roles->first()) {
                $this->roleUserRepository->create(['role_id' => $request->role_id, 'user_id' => $user->id]);
            } else {
                $this->roleUserRepository->where('user_id', $user->id)->update(['role_id' => $request->role_id]);
            }

            DB::commit();
            return redirect()->route('users')->with('success', 'Usuário editado com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('users')->with('warning',  $e->getMessage());
        }
    }

    public function delete($id)
    {
        $user = $this->repository->find($id);
        if (!$user) {
            return redirect()->back();
        }

        if($user->id == Auth::user()->id) {
            return redirect()->back();
        }

        if($user->isAdmin() && !Auth::user()->isAdmin()) {
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $user->delete();
            DB::commit();
            return redirect()->route('users')->with('success', 'Usuário removido com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('users')->with('warning',  $e->getMessage());
        }
    }
}
