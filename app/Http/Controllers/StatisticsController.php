<?php

namespace App\Http\Controllers;

use App\Models\Analytics;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index() {
        $data = [
            'stat' => [
                [
                    'title' => 'Население школы',
                    'value' => Analytics::number_of_people()
                ],
                [
                    'title' => 'Классов',
                    'value' => Analytics::number_of_classes()
                ],
                [
                    'title' => 'Учеников (М)',
                    'value' => Analytics::number_of_people([RoleController::ROLE_STUDENT], ['Мужской']),
                ],
                [
                    'title' => 'Учеников (Ж)',
                    'value' => Analytics::number_of_people([RoleController::ROLE_STUDENT], ['Женский']),
                ],
                [
                    'title' => 'Учителей',
                    'value' => Analytics::number_of_people([RoleController::ROLE_TEACHER, RoleController::ROLE_CLASSROOM_TEACHER]),
                ],
                [
                    'title' => 'Класс. руководителей',
                    'value' => Analytics::number_of_people([RoleController::ROLE_CLASSROOM_TEACHER]),
                ],
                [
                    'title' => 'Зам-ов директора',
                    'value' => Analytics::number_of_people([RoleController::ROLE_ASSOCIATE_DIRECTOR]),
                ],
                [
                    'title' => 'Директоров',
                    'value' => Analytics::number_of_people([RoleController::ROLE_DIRECTOR]),
                ],
                [
                    'title' => 'Администраторов',
                    'value' => Analytics::number_of_people([RoleController::ROLE_ADMIN]),
                ]
            ]
        ];

        return view('statistics.index', $data);
    }
}
