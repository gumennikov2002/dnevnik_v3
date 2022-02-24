<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    protected $table = 'classes';
    public $timestamps = false;

    protected $fillable = [
        'classroom_id',
        'user_id'
    ];

    protected static function init_config() {
        return [
            'config' => [
                'title'         => '',
                'page_title'    => '',
                'page_subtitle' => '',
                'table_heads'   => ['#', 'Класс', 'ФИО Ученика'],
                'table_body'    => [],
                'hide_actions'  => [
                    'edit' => true,
                    'delete' => false
                ],
                'modal_fields' => [
                    'classroom' => [
                        'field_type' => 'text',
                        'text'  => 'Класс',
                        'value' => ''
                    ],
                    'classroom_id' => [
                        'field_type'  => 'input',
                        'type'        => 'text',
                        'name'        => 'classroom_id',
                        'placeholder' => 'Класс',
                        'value'       => '',
                        'hidden'      => true
                    ]
                ]
            ],
            'validate' => [
                'user_id'      => 'required',
                'classroom_id' => 'required',
            ],
            'references' => [
                'classroom_id' => [
                    'model' => 'Classroom',
                    'get'   => 'class'
                ],
                'user_id'    => [
                    'model' => 'User',
                    'get'   => 'full_name'
                ]
            ]
        ];
    }
}
