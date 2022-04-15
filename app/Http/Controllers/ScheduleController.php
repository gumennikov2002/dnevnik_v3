<?php

namespace App\Http\Controllers;

use App\Models\Cabinet;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function teachers_index() {
        $own_subjects_ids = [];
        $own_subjects = Subject::where('teacher_id', $this->USER_INFO->id)->get();

        foreach ($own_subjects as $subject) {
            $own_subjects_ids[] = $subject->id;
        }

        $schedules = Schedule::whereIn('subject_id', $own_subjects_ids)->get();

        foreach ($schedules as $index => $item) {
            $schedules[$index]['classroom'] = Classroom::find($item->classroom_id)->class;
            $schedules[$index]['cabinet'] = Cabinet::find($item->cabinet_id)->num;
        }

        $data = [
          'schedules' => $schedules
        ];

        return view('schedule.teachers_index', $data);
    }

    public function index(Request $request) {
        $data = [
            'classrooms' => Classroom::all(),
            'user_role'  => $this->USER_INFO->role
        ];

        $view = 'schedule.index';

        if ($request->has('classroom_id')) {
            $schedules = Schedule::where('classroom_id', $request->get('classroom_id'))->get();
    
            foreach ($schedules as $key => $schedule) {
                $subject = Subject::where('id', $schedule->subject_id)->first();
                $cabinet = Cabinet::where('id', $schedule->cabinet_id)->first();
                $teacher = User::where('id', $subject->teacher_id)->first();
    
                $schedules[$key]['subject'] = $subject->name;
                $schedules[$key]['cabinet'] = $cabinet->num;
                $schedules[$key]['teacher'] = $teacher->full_name;
            }
    
            $data['schedules'] = $schedules;
        }

        return view($view, $data);
    }

    public function get_record(Request $request) {
        $record = Schedule::find($request->post('id'));
        $record['teacher_id'] = Subject::where('id', $record->subject_id)->first()->teacher_id;
        return $record;
    }

    public function get_subjects() {
        return response([
            'subjects' => Subject::all(),
            'cabinets' => Cabinet::all()
        ]);
    }

    public function get_teachers(Request $request) {
        $subject = Subject::find($request->post('subject_id'));
        return User::find($subject->teacher_id);
    }

    public function save(Request $request) {
        if (empty($request)) {
            return false;
        }

        if ($request->post('id') !== null) {
            $record = Schedule::find($request->post('id'));
            $updates = $request->all();
            $record->update($updates);
        } else {
            $validation = $request->validate([
                'classroom_id' => 'required|integer',
                'subject_id'   => 'required|integer',
                'cabinet_id'   => 'required|integer',
                'day_of_week'  => 'required|integer',
                'from_time'    => 'required',
                'to_time'      => 'required'
            ]);
    
            $record = Schedule::create($validation);
        }


        if ($record) {
            return response($record);
        }
    }

    public function delete(Request $request) {
        return Schedule::destroy($request->post('id'));
    }

    public function get_link() {
        if ($this->USER_INFO->role === RoleController::ROLE_STUDENT) {
            $classroom_id = User::get_classroom($this->USER_INFO->id)->id;
            return '/schedule?classroom_id='.$classroom_id;
        }

        $is_teacher = $this->USER_INFO->role === RoleController::ROLE_TEACHER;
        $is_classroom_teacher = $this->USER_INFO->role === RoleController::ROLE_CLASSROOM_TEACHER;

        if ($is_teacher || $is_classroom_teacher) {
            return '/teachers_schedule';
        }

        return '/schedule';
    }
}
