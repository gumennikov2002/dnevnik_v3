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
                'icon'  => 'users',
                'roles' => []
            ],
            'cabinets' => [
                'url'   => '/cabinets_crud',
                'icon'  => 'columns',
                'roles' => []
            ]
        ];

        return parent::__construct();
        
    }
    
    public function load() {
        $data = [
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
        ];

        return response($data);
    }
}
