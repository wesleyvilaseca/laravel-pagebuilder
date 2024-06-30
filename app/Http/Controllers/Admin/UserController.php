<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $data['title']      = 'Users';
        $data['toptitle']   = 'Users';
        $data['list']       = User::all();
        $data['users']      = true;

        return view('admin.users.index', $data);
    }

    public function create()
    {
        $data['title']      = 'New Post';
        $data['toptitle']   = 'New Post';
        $data['roles_list']      = Role::all();
        $data['users']      = true;
        $data['action']     = route('user.save');

        return view('admin.users.create', $data);
    }

    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back();
        }

        $data['title']      = 'Edit user ' . $user->title;
        $data['toptitle']   = 'Edit user ' . $user->title;
        $data['roles_list']      = Role::all();
        $data['users']      = true;
        $data['user']       = $user;
        $data['role_user'] = $user->roles->first()->id;
        $data['action']     = route('user.update', $user->id);

        return view('admin.users.create', $data);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name'         => ['required'],
            'email'   => ['required'],
            'password'   => ['required'],
            'role_id'   => ['required']
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with('errors', $validate->errors());
        }

        $result = user::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)

        ]);

        if (!$result) {
            return redirect()->back()->with('warning', 'error on execution operation');
        }

        RoleUser::create(['role_id' => $request->role_id, 'user_id' => $result->id]);

        return redirect()->route('users')->with('success', 'success on create role');
    }

    public function update($id, Request $request)
    {
        $user = user::find($id);
        if (!$user) {
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            'name'         => ['required'],
            'email'         => ['required'],
            'role_id'       => ['required']
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with('errors', $validate->errors());
        }

        $data = [
            'name'         => $request->name,
            'email'        => $request->email
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $result = User::where('id', $id)->update($data);

        if (!$result) {
            return redirect()->back()->with('warning', 'error on execution operation');
        }

        RoleUser::where('user_id', $user->id)->update(['role_id' => $request->role_id]);

        return redirect()->route('users')->with('success', 'success on edit role');
    }

    public function delete($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back();
        }

        $result = User::where('id', $id)->delete();

        if (!$result) {
            return redirect()->back()->with('warning', 'error on execution operation');
        }

        return redirect()->route('users')->with('success', 'success on delete role');
    }
}
