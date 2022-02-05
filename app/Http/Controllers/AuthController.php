<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index() {
        return view('auth.index');
    }

    public function check(Request $request) {
        if (!empty($request)) {
            $phone = '7'.substr($request->post('phone'), 1);
            $password = $request->post('password');

            $user = User::where('phone', $phone)->get();

            if (isset($user[0])) {
                $user = $user[0];
                $check_password = Hash::check($password, $user->password);
                
                if ($check_password) {
                    session()->put('session_user_id', $user->id);
                    return response(true);
                } else {
                    return response(null, 500);
                }
            } else {
                return response(null, 500);
            }
        } else {
            return response(null, 500);
        } 
    }
}
