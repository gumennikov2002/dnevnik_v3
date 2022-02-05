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
            'modal_title'  => 'Добавить пользователя',
            'table_heads'  => ['#', 'ФИО', 'Телефон', 'Эл. почта', 'Дата рождения', 'Доп. инфо', 'Тип'],
            'table_body'   => json_decode(User::all()),
            'modal_fields' => [
                'full_name' => [
                    'field_type'  => 'input',
                    'type'        => 'text',
                    'name'        => 'full_name',
                    'placeholder' => 'Фамилия Имя Отчество'
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
                'role' => [
                    'field_type'  => 'select',
                    'name'        => 'role',
                    'placeholder' => 'Тип пользователя',
                    'options'     => [
                        'student' => [
                            'label' => 'Ученик',
                            'value' => 'STUDENT'
                        ],
                        'teacher' => [
                            'label' => 'Учитель',
                            'value' => 'TEACHER'
                        ],
                        'director' => [
                            'label' => 'Директор',
                            'value' => 'DIRECTOR'
                        ]
                    ]
                ]
            ]
        ];
        $this->VALIDATE = [
            'full_name'       => 'required',
            'phone'           => 'required|integer',
            'date_of_birth'   => 'required',
            'role'            => 'required',
            'email'           => 'nullable',
            'additional_info' => 'nullable'
        ];
    }
}
