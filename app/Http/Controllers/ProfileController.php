<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Classroom;
use App\Models\User;
use App\Models\User_settings;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use JetBrains\PhpStorm\NoReturn;

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

        if ($data['user']->role === 'Ученик') {
            $class_id = Classes::where('user_id', $data['user']->id)->first()->classroom_id;
            $classroom = Classroom::find($class_id)->first();
            $data['user']['classroom']  = $classroom->class;
            $data['user']['classroom_teacher'] = User::where('id', $classroom->teacher_id)->first()->full_name;
        }

        return view('profile.index', $data);
    }

    public function update(Request $request) {
        $user = User::find($this->USER_INFO->id);
        $validate_fields = [];

        if ($request->email || $request->phone) {
            $validate_fields = [
                'email' => 'required',
                'phone' => 'required|integer'
            ];
        }

        $validation = $request->validate($validate_fields);

        if ($request->profile_pic) {
            $validate_fields['profile_pic'] = 'required|mimes:jpg,png,jpeg';

            if ($request->validate(['profile_pic' => 'required|mimes:jpg,png,jpeg'])) {
                $image_name = time().'-'.$request->phone.'.'.$request->profile_pic->extension();
                $request->profile_pic->move(public_path('images'), $image_name);
                $validation['profile_pic'] = 'images/'.$image_name;
            }
        }

        if ($validation) {
            $user->update($validation);
            return redirect(route('profile'));
        }
    }

    public function change_password(Request $request) {
        $user = User::find($this->USER_INFO->id);
        $old_password = $request->old_password;
        $new_password = $request->new_password;
        $repeat_password = $request->repeat_password;

        $check_password = Hash::check($old_password, $user->password);

        $fields = [
            'old_password'    => 'required',
            'new_password'    => 'required|min:6',
            'repeat_password' => 'required'
        ];

        $validate = $request->validate($fields);

        if ($new_password === $repeat_password && $validate && $check_password) {
            $user->update(['password' => Hash::make($new_password)]);
            return redirect(route('profile.logout'));
        }

        return back();
    }

    public function logout() {
        if (session()->has('session_user_id')) {
            session()->pull('session_user_id');
            return redirect('/auth');
        }
    }

    public function classmates() {
        if ($this->USER_INFO->role === RoleController::ROLE_CLASSROOM_TEACHER) {
            $classroom = Classroom::where('teacher_id', $this->USER_INFO->id)->first();
            $class = Classes::where('classroom_id', $classroom->id)->first();
        } else {
            $class = Classes::where('user_id', $this->USER_INFO->id)->first();
            $classroom = Classroom::find($class->classroom_id)->first();
        }

        $classroom_teacher = User::where('id', $classroom->teacher_id)->first();
        $classmates = Classes::where('classroom_id', $class->classroom_id)->get();
        $classmates_info = [];

        foreach($classmates as $classmate) {
            $classmates_info[] = User::find($classmate->user_id);
        }

        $data = [
            'user' => $this->USER_INFO,
            'classroom' => $classroom,
            'classroom_teacher' => $classroom_teacher,
            'classmates' => $classmates_info
        ];

        return view('classmates.index', $data);
    }

    public function change_theme() {
        $new_theme = User_settings::change_theme($this->USER_INFO->id);
        return redirect()->back();
    }

    public function detect_theme() {
        if (!isset($this->USER_INFO)) {
          return false;
        }

        return User_settings::detect_theme($this->USER_INFO->id);
    }

    public function classroom_schedule_link() {
        if ($this->USER_INFO->role !== RoleController::ROLE_CLASSROOM_TEACHER) {
            return false;
        }

        $classroom_id = Classroom::where('teacher_id', $this->USER_INFO->id)->first()->id;
        return "schedule?classroom_id=$classroom_id";
    }
}
