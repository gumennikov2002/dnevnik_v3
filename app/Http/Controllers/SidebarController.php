<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SidebarController extends Controller
{
    public const ROLES          = [];
    public const MENU           = [];
    public const PROFILE_CONFIG = [];

    public function __construct()
    {
        
        $this->ROLES = [
            'admin'             => [],
            'director'          => [],
            'assoc_director'    => [],
            'classroom_teacher' => [],
            'teacher'           => []
        ];

        $this->MENU = [
            'users' => [
                'url'   => '/users_crud',
                'icon'  => 'people',
                'title' => 'Пользователи',
                'class' => 'users',
                'roles' => []
            ],
            'cabinets' => [
                'url'   => '/cabinets_crud',
                'icon'  => 'tablet-portrait',
                'title' => 'Кабинеты',
                'class' => 'cabinets',
                'roles' => []
            ],
            'classrooms' => [
                'url'   => '/classrooms_crud',
                'icon'  => 'accessibility',
                'title' => 'Классы',
                'class' => 'classrooms',
                'roles' => []
            ],
            'subjects'   => [
                'url'   => '/subjects_crud',
                'icon'  => 'layers',
                'title' => 'Предметы',
                'class' => 'subjects',
                'roles' => []
            ]
        ];

        return parent::__construct();
        
    }
    
    public function load() {
        return response([
            'roles'   => $this->ROLES,
            'menu'    => $this->MENU,
            'profile' => [
                'profile_pic'  => $this->USER_INFO->profile_pic,
                'profile_name' => $this->USER_INFO->full_name,
                'profile_menu' => [
                    'settings' => [
                        'title' => 'Настройки',
                        'link'  => '#'
                    ],
                    'profile' => [
                        'title' => 'Профиль',
                        'link'  => '/profile'
                    ]
                ]
            ]
        ]);
    }
}
