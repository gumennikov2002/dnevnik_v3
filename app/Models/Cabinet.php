<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabinet extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'num'
    ];

    protected static function init_config() {
        return [
            'config' => [
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
            ],
            'validate' => ['num' => 'required']
        ];
    }
}
