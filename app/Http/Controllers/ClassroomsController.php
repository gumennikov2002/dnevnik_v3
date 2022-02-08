<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
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
            'table_body'  => json_decode(Classroom::all()),
            'modal_fields' => [
                'class' => [
                    'field_type'  => 'input',
                    'type'        => 'text',
                    'name'        => 'class',
                    'placeholder' => 'Класс'
                ],
                'teacher_id' => [
                    'field_type'  => 'input',
                    'type'        => 'text',
                    'name'        => 'teacher_id',
                    'placeholder' => 'Классный руководитель'
                ]
            ]
        ];
        $this->VALIDATE = [
            'class'      => 'required|unique:classrooms',
            'teacher_id' => 'required|integer'
        ];
    }
}
