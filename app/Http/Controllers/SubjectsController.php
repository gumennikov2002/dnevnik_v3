<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class SubjectsController extends CrudController
{
    public function __construct()
    {
        $this->MODEL_NAME = 'App\Models\Subject';
        parent::__construct();

        $teachers = User::where('role', 'Учитель')->orWhere('role', 'Классный руководитель')->get();

        if (count($teachers) === 0) {
            $this->CONFIG['modal_fields']['teacher_id']['options'] = [
                'empty' => [
                    'label' => 'Свободных учителей нет',
                    'value' => null
                ]
            ];
        } 
        
        if (count($teachers) > 0){
            foreach ($teachers as $key => $value) {
                $this->CONFIG['modal_fields']['teacher_id']['options'][$key] = [
                    'label' => $value->full_name,
                    'value' => $value->id
                ];
            }
        }
    }

    public function get_subjects() {
        return Subject::all();
    }
}
