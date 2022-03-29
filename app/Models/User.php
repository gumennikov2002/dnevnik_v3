<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'gender',
        'phone',
        'password',
        'email',
        'additional_info',
        'date_of_birth',
        'role',
        'profile_pic'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        'profile_pic',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    public static function get_classroom($user_id) {
        $class = Classes::where('user_id', $user_id)->first();
        return Classroom::find($class->classroom_id);
    }

    protected static function init_config() {
        return [
            'config'     => [
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
            ],
            'validate'   => [
                'full_name'       => 'required',
                'phone'           => 'required|integer',
                'date_of_birth'   => 'required',
                'role'            => 'required',
                'email'           => 'nullable',
                'additional_info' => 'nullable',
                'gender'          => 'required'
            ],
        ];
    }
}
