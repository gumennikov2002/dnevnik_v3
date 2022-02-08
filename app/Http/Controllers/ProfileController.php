<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index() {
        $data = [
            'user' => $this->USER_INFO
        ];

        $birth_date = date('Y-m-d H:i:s', strtotime($data['user']->date_of_birth));
        $current_date = date('Y-m-d H:i:s');
        $sec_in_year = 31536000;
        $date_diff = strtotime($current_date) - strtotime($birth_date);
        $user_age = floor($date_diff / $sec_in_year);

        $data['user']['age'] = $user_age;

        return view('profile.index', $data);
    }

    public function logout() {
        if (session()->has('session_user_id')) {
            session()->pull('session_user_id');
            return redirect('/auth');
        }
    }
}
