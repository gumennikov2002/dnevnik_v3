<?php

namespace App\Http\Controllers;

use App\Models\Cabinet;
use Illuminate\Http\Request;
use stdClass;

class CabinetController extends CrudController
{
    public function __construct()
    {
        $this->MODEL_NAME = 'App\Models\Cabinet';
        parent::__construct();
    }
}
