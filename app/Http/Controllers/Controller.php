<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public const USER_ID = null;
    public const USER_INFO = null;

    public function __construct()
    {
        $this->USER_ID = session('session_user_id');
        if ($this->USER_ID) {
            $this->USER_INFO = User::where('id', $this->USER_ID)->get()[0];
        }
    }
}
