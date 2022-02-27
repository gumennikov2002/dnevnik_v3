<?php

namespace App\Http\Controllers;

use App\Models\Cabinet;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index() {
        return view('schedule.index');
    }

    public function get_modal_fields() {
        $data = [
            'subjects' => Subject::all(),
            'teachers'  => User::all(),
            'cabinets' => Cabinet::all()
        ];

        return response($data);
    }
}
