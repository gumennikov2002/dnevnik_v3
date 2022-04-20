<?php

namespace App\Http\Controllers;

use App\Http\Controllers\RoleController;
use App\Models\User;
use Illuminate\Http\Request;

class SidebarController extends Controller
{
    public const ROLES          = [];
    public const MENU           = [];
    public const PROFILE_CONFIG = [];

    public function __construct()
    {
        $schedule = new ScheduleController();
        $profile = new ProfileController();

        $this->MENU = [
            'statistics' => [
              'url' => '/statistics',
              'icon' => 'bar-chart',
              'title' => 'Статистика',
              'class' => 'statistics',
              'roles' => [
                  RoleController::ROLE_ADMIN,
                  RoleController::ROLE_DIRECTOR,
                  RoleController::ROLE_ASSOCIATE_DIRECTOR
              ]
            ],
            'schedule' => [
                'url'   => $schedule->get_link(),
                'icon'  => 'calendar',
                'title' => 'Расписание',
                'class' => 'schedule',
                'roles' => [
                    RoleController::ROLE_TEACHER,
                    RoleController::ROLE_CLASSROOM_TEACHER,
                    RoleController::ROLE_STUDENT,
                    RoleController::ROLE_ADMIN,
                    RoleController::ROLE_ASSOCIATE_DIRECTOR,
                    RoleController::ROLE_DIRECTOR,
                ]
            ],
            'classroom_teachers_schedule' => [
              'url' => $profile->classroom_schedule_link(),
              'icon' => 'calendar-clear',
              'title' => 'Расписание моего класса',
              'class' => 'classroom_teachers_schedule',
              'roles' => [
                  RoleController::ROLE_CLASSROOM_TEACHER
              ]
            ],
            'users' => [
                'url'   => '/users_crud',
                'icon'  => 'people',
                'title' => 'Пользователи',
                'class' => 'users',
                'roles' => [
                    RoleController::ROLE_ASSOCIATE_DIRECTOR,
                    RoleController::ROLE_DIRECTOR,
                    RoleController::ROLE_ADMIN
                ]
            ],
            'classmates' => [
                'url'   => '/classmates',
                'icon'  => 'man',
                'title' => 'Мой класс',
                'class' => 'classmates',
                'roles' => [
                    RoleController::ROLE_CLASSROOM_TEACHER,
                    RoleController::ROLE_STUDENT,
                ]
            ],
            'cabinets' => [
                'url'   => '/cabinets_crud',
                'icon'  => 'tablet-portrait',
                'title' => 'Кабинеты',
                'class' => 'cabinets',
                'roles' => [
                    RoleController::ROLE_ASSOCIATE_DIRECTOR,
                    RoleController::ROLE_DIRECTOR,
                    RoleController::ROLE_ADMIN
                ]
            ],
            'classrooms' => [
                'url'   => '/classrooms_crud',
                'icon'  => 'accessibility',
                'title' => 'Классы',
                'class' => 'classrooms',
                'roles' => [
                    RoleController::ROLE_TEACHER,
                    RoleController::ROLE_DIRECTOR,
                    RoleController::ROLE_ASSOCIATE_DIRECTOR,
                    RoleController::ROLE_ADMIN
                ]
            ],
            'subjects'   => [
                'url'   => '/subjects_crud',
                'icon'  => 'book',
                'title' => 'Предметы',
                'class' => 'subjects',
                'roles' => [
                    RoleController::ROLE_DIRECTOR,
                    RoleController::ROLE_ASSOCIATE_DIRECTOR,
                    RoleController::ROLE_ADMIN
                ]
            ],
            'theme'     => [
                'url'   => '/profile/change_theme',
                'icon'  => 'contrast',
                'title' => 'Сменить тему',
                'class' => 'theme',
                'roles' => []
            ]
        ];

        return parent::__construct();

    }

    public function load() {
        return response([
            'roles'   => RoleController::ROLES,
            'menu'    => $this->compile_menu(),
            'profile' => [
                'profile_pic'  => $this->USER_INFO->profile_pic,
                'profile_name' => $this->USER_INFO->full_name,
                'profile_menu' => [
                    'profile' => [
                        'title' => 'Профиль',
                        'link'  => '/profile'
                    ]
                ]
            ]
        ]);
    }

    private function compile_menu() {
        $menu = [];

        foreach ($this->MENU as $key => $item) {
            $is_allow = in_array($this->USER_INFO->role, $item['roles']);
            $empty = count($item['roles']) === 0;

            if ($is_allow || $empty) {
                $menu[$key] = $item;
            }
        }

        return $menu;
    }
}
