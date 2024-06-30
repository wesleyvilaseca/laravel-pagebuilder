<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        $users =  User::paginate(5);
        return response()->json($users);
    }

    public function show($id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($user);
    }

    public function store(UserCreateRequest $request) {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' =>  bcrypt($request->password)
        ]);

        return response()->json($user, Response::HTTP_CREATED);
    }

    public function update($id, UserUpdateRequest $request) {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], Response::HTTP_NOT_FOUND);
        }

        if ($request->has('first_name')) {
            $user->name = $request->first_name;
        }

        if ($request->has('last_name')) {
            $user->name = $request->last_name;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json($user, Response::HTTP_OK);
    }

    public function destroy($id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], Response::HTTP_NOT_FOUND);
        }

        $user->delete();

        return response()->json(['message' => 'Usuário excluído com sucesso.'], Response::HTTP_OK);
    }
}
