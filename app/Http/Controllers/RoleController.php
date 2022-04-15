<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    public const ROLE_STUDENT = 'Ученик';
    public const ROLE_TEACHER = 'Учитель';
    public const ROLE_CLASSROOM_TEACHER = 'Классный руководитель';
    public const ROLE_ASSOCIATE_DIRECTOR = 'Зам. директора';
    public const ROLE_DIRECTOR = 'Директор';
    public const ROLE_ADMIN = 'Админ';

    public const ROLES = [
        self::ROLE_STUDENT,
        self::ROLE_TEACHER,
        self::ROLE_CLASSROOM_TEACHER,
        self::ROLE_ASSOCIATE_DIRECTOR,
        self::ROLE_DIRECTOR,
        self::ROLE_ADMIN,
    ];
}
