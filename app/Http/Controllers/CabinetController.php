<?php

namespace App\Http\Controllers;

use App\Models\Cabinet;
use Illuminate\Http\Request;
use stdClass;

class CabinetController extends CrudController
{
    public function __construct()
    {
        parent::__construct();

        $this->MODEL_NAME = 'App\Models\Cabinet';
        $this->CONFIG = [
            'title'       => 'Кабинеты',
            'page_title'  => 'Кабинеты',
            'table_heads' => ['#', 'Номер кабинета'],
            'table_body'  => json_decode(Cabinet::all()),
            'modal_fields' => [
                'class' => [
                    'field_type'  => 'input',
                    'type'        => 'text',
                    'name'        => 'class',
                    'placeholder' => 'Класс'
                ],
                'teacher_id' => [
                    'field_type'  => 'text',
                    'name'        => 'teacher_id',
                    'placeholder' => 'Классный руководитель'
                ]
            ]
        ];
        $this->VALIDATE = [
            'class'      => 'required|unique:classrooms',
            'teacher_id' => 'required|numeric'
        ];
    }
}
