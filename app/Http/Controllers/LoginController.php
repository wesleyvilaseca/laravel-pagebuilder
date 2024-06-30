<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    //
    public function index()
    {
        $data['title'] = 'Login';
        return view('auth.login', $data);
    }

    public function auth(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email'         => ['required'],
            'password'      => ['required']
        ]);

        if ($validate->fails()) {
            return Redirect::back()->with('error', $validate->errors());
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return Redirect::back()->with('error', 'User not found');
        };

        return Redirect::route('painel');
    }

    public function logout() {
        Auth::logout();
        return Redirect::route('login');
    }
}
