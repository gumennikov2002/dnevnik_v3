<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'name',
        'teacher_id'
    ];

    protected static function init_config() {
        return [
            'config'     => [
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
            ],
            'validate'   => [
                'name'      => 'required',
                'teacher_id' => 'required|integer'
            ],
            'references' => [
                'teacher_id' => [
                    'model' => 'User',
                    'get'   => 'full_name'
                ]
            ]
        ];
    }
}
