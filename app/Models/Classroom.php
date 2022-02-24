<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'class',
        'teacher_id'
    ];

    protected static function init_config() {
        return [
            'config'     => [
                'title'            => 'Классы',
                'page_title'       => 'Классы',
                'table_heads'      => ['#', 'Класс', 'Классный руководитель'],
                'table_body'       => [],
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
            ],
            'validate'   => [
                'class'      => 'required|unique:classrooms',
                'teacher_id' => 'required|integer'
            ],
            'references' => [
                'teacher_id' => [
                    'model' => 'User',
                    'get'   => 'full_name'
                ]
            ],
            'table_link' => route('class').'?page=1&class_id='
        ];
    }
}
