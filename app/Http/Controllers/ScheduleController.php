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
    public function index(Request $request) {
        $data = [
            'classrooms' => Classroom::all()
        ];

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

        return view('schedule.index', $data);
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
}
