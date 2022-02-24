<?php

namespace App\Http\Controllers;

use App\Models\User;

class UsersController extends CrudController
{
    public function __construct()
    {
        $this->MODEL_NAME = 'App\Models\User';
        parent::__construct();
    }
}
