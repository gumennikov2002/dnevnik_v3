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
            'table_body'   => [],
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
                'role' => [
                    'field_type'  => 'select',
                    'name'        => 'role',
                    'placeholder' => 'Тип пользователя',
                    'options'     => [
                        'student' => [
                            'label' => 'Ученик',
                            'value' => 'Ученик'
                        ],
                        'teacher' => [
                            'label' => 'Учитель',
                            'value' => 'Учитель'
                        ],
                        'classroom_teacher' => [
                            'label' => 'Классный руководитель',
                            'value' => 'Классный руководитель'
                        ],
                        'pre_director' => [
                            'label' => 'Зам. директора',
                            'value' => 'Зам. директора'
                        ],
                        'director' => [
                            'label' => 'Директор',
                            'value' => 'Директор'
                        ],
                        'admin' => [
                            'label' => 'Админ',
                            'value' => 'Админ'
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
            'additional_info' => 'nullable',
            'gender'          => 'required'
        ];
    }
}
