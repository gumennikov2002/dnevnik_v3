<?php

namespace App\Http\Controllers;

use App\Models\User;

class UsersController extends CrudController
{
    public function __construct()
    {
        parent::__construct();
        $this->MODEL_NAME = 'App\Models\User';
        $this->CONFIG = [
            'title'        => 'Пользователи',
            'page_title'   => 'Пользователи',
            'table_heads'  => ['#', 'ФИО', 'Пол', 'Телефон', 'Эл. почта', 'Дата рождения', 'Доп. инфо', 'Роль'],
            'table_body'   => json_decode(User::all()),
            'modal_fields' => [
                'full_name' => [
                    'field_type'  => 'input',
                    'type'        => 'text',
                    'name'        => 'full_name',
                    'placeholder' => 'Фамилия Имя Отчество'
                ],
                'gender' => [
                    'field_type'  => 'select',
                    'name'        => 'gender',
                    'placeholder' => 'Пол',
                    'options'     => [
                        'male'  => [
                            'label' => 'Мужской',
                            'value' => 'Мужской'
                        ],
                        'female' => [
                            'label' => 'Женский',
                            'value' => 'Женский'
                        ]
                    ]
                ],
                'phone' => [
                    'field_type'  => 'input',
                    'type'        => 'text',
                    'name'        => 'phone',
                    'placeholder' => 'Телефон'
                ],
                'date_of_birth' => [
                    'field_type'  => 'input',
                    'type'        => 'text',
                    'name'        => 'date_of_birth',
                    'placeholder' => 'Дата рождения'
                ],
                'email' => [
                    'field_type'  => 'input',
                    'type'        => 'email',
                    'name'        => 'email',
                    'placeholder' => 'Электронный адрес'
                ],
                'additional_info' => [
                    'field_type'  => 'textarea',
                    'name'        => 'additional_info',
                    'placeholder' => 'Доп. инфо'
                ],
                'role_id' => [
                    'field_type'  => 'select',
                    'name'        => 'role_id',
                    'placeholder' => 'Тип пользователя',
                    'options'     => [
                        'student' => [
                            'label' => 'Ученик',
                            'value' => 0
                        ],
                        'teacher' => [
                            'label' => 'Учитель',
                            'value' => 1
                        ],
                        'director' => [
                            'label' => 'Директор',
                            'value' => 2
                        ]
                    ]
                ]
            ]
        ];
        $this->VALIDATE = [
            'full_name'       => 'required',
            'phone'           => 'required|integer',
            'date_of_birth'   => 'required',
            'role_id'         => 'required',
            'email'           => 'nullable',
            'additional_info' => 'nullable',
            'gender'          => 'required'
        ];
    }
}
