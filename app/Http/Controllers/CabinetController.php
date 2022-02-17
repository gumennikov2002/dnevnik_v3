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
            'title'         => 'Кабинеты',
            'page_title'    => 'Кабинеты',
            'table_heads'   => ['#', 'Номер кабинета'],
            'table_body'    => [],
            'table_filters' => [],
            'modal_fields'  => [
                'num'      => [
                    'field_type'  => 'input',
                    'type'        => 'text',
                    'name'        => 'num',
                    'placeholder' => 'Номер кабинета'
                ]
            ]
        ];
        $this->VALIDATE = [
            'num' => 'required'
        ];
    }
}
