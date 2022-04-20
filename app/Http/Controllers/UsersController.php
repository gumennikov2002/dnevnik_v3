<?php

namespace App\Http\Controllers;

use App\Models\User;

class UsersController extends CrudController
{
    public function __construct()
    {
        $this->MODEL_NAME = 'App\Models\User';
        $this->ACCESSED_ROLES = [
            RoleController::ROLE_ADMIN
        ];
        parent::__construct();
    }

    public static function get_user_role($user_id) {
        return User::find($user_id)->role;
    }
}
