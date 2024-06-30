<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function index()
    {
        $data['title'] = 'Register';

        return view('auth.register', $data);
    }

    public function create(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required'],
            'password' => ['required']
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with('errors', $validate->errors());
        }

        $exist = User::where('email', $request->email)->first();
        if ($exist) {
            return redirect()->back()->with('warning', 'Já existe usuário com esse email');
        }

        $result = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password'  => Hash::make($request->password),
        ]);

        if(!$result){
            return redirect()->back()->with('error', 'Erro na operação');
        }

        return redirect()->route('login')->with('success', 'Usuario criado com sucesso');
    }
}
