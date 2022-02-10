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
            'title' => 'Классы',
            'page_title'  => 'Классы',
            'table_heads' => ['#', 'Класс', 'Классный руководитель'],
            'table_body'  => [],
            'modal_fields' => [
                'class' => [
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
                
        $classroom_teachers = User::where('role', 'Классный руководитель')->get();

        foreach ($classroom_teachers as $key => $value) {
            $this->CONFIG['modal_fields']['teacher_id']['options'][$key] = [
                'label' => $value->full_name,
                'value' => $value->id
            ];
        }
    }
}
