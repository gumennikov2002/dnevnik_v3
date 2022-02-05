<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index() {
        return 'Profile';
    }

    public function logout() {
        if (session()->has('session_user_id')) {
            session()->pull('session_user_id');
            return redirect('/auth');
        }
    }
}
