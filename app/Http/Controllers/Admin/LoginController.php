<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->passes()) {
            $email = $request->input('email');
            $password = $request->input('password');

            if (Auth::guard('admin')->attempt(['email' => $email, 'password' => $password])) {
                return redirect()->route('admin.dashboard');
                if(Auth::guard('admin')->user()->role != 'admin');
                Auth::guard('admin')->logout();
                return redirect()->route('admin.login')->with('error', 'You are not authoized to access this page,');


            } else {
                return redirect()->route('admin.login')->with('error', 'Either Email or password is incorrect');
            }
        } else {
            return redirect()->route("admin.login")
                ->withInput()
                ->withErrors($validator);
        }
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route("admin.login");
    }

}
