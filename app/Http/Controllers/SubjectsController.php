<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SubjectsController extends CrudController
{
    public function __construct()
    {
        parent::__construct();

        $this->MODEL_NAME = 'App\Models\Subject';
        $this->CONFIG = [
            'title'         => 'Предметы',
            'page_title'    => 'Предметы',
            'table_heads'   => ['#', 'Название', 'Преподаватель'],
            'table_body'    => [],
            'modal_fields'  => [
                'name'     => [
                    'field_type'  => 'input',
                    'type'        => 'text',
                    'name'        => 'name',
                    'placeholder' => 'Название предмета'
                ],
                'teacher_id' => [
                    'field_type'  => 'select',
                    'name'        => 'teacher_id',
                    'placeholder' => 'Преподаватель',
                    'options'     => []
                ]
            ]
        ];
        $this->VALIDATE = [
            'name'      => 'required',
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
}
