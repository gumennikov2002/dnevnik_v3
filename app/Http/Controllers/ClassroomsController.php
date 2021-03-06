<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;

class ClassroomsController extends CrudController
{
    public function __construct()
    {
        parent::__construct();

        $this->MODEL_NAME = 'App\Models\Classroom';
        $this->CONFIG = [
            'title'            => 'Классы',
            'page_title'       => 'Классы',
            'table_heads'      => ['#', 'Класс', 'Классный руководитель'],
            'table_body'       => [],
            'table_filters'    => [],
            'modal_fields'     => [
                'class'        => [
                    'field_type'  => 'input',
                    'type'        => 'text',
                    'name'        => 'class',
                    'placeholder' => 'Класс'
                ],
                'teacher_id' => [
                    'field_type'  => 'select',
                    'name'        => 'teacher_id',
                    'placeholder' => 'Классный руководитель',
                    'options'     => []
                ]
            ]
        ];
        $this->VALIDATE = [
            'class'      => 'required|unique:classrooms',
            'teacher_id' => 'required|integer'
        ];
        $this->REFERENCES = [
            'teacher_id' => [
                'model' => 'User',
                'get'   => 'full_name'
            ]
        ];

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

    
    public function create(Request $request) {
        $validatedFields = $request->validate($this->VALIDATE);
 
        $record = $this->MODEL_NAME::create($validatedFields);
        $user = User::where('id', $record->teacher_id);
        $user->update(['role' => 'Классный руководитель']);

        if ($record) {
            return response($record);
        }
    }

    public function update(Request $request) {
        $record = $this->MODEL_NAME::find($request->id);

        $prev_user = User::find($record->teacher_id)->first();
        $prev_user_classrooms_count = $this->MODEL_NAME::where('teacher_id', $prev_user->id)->count();

        if ($prev_user_classrooms_count <= 1) {
            $prev_user->update(['role' => 'Учитель']);
        }
        
        User::where('id', $request->teacher_id)->first()->update(['role' => 'Классный руководитель']);

        $updates = $request->all();
        $record->update($updates);

        if ($record) {
            return response($record);
        }
    }

    public function delete(Request $request) {
        $record = $this->MODEL_NAME::find($request->id);
        $user = User::where('id', $record->teacher_id)->first();
        $count_classrooms = $this->MODEL_NAME::where('teacher_id', $user->id)->count();

        if ($count_classrooms <= 1) {
            $user->update(['role' => 'Учитель']);
        }

        $this->MODEL_NAME::destroy($request->input('id'));
    }
}
