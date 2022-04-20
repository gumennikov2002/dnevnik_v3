<?php

namespace App\Models;

use App\Http\Controllers\RoleController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    use HasFactory;

    public static function number_of_classes() {
        return Classroom::all()->count();
    }

    public static function number_of_people($roles = [], $genders = []) {
        if (empty($roles) && empty($genders)) {
            return User::all()->count();
        }

        if (empty($genders)) {
            return User::whereIn('role', $roles)->count();
        }

        return  User::whereIn('role', $roles)->whereIn('gender', $genders)->count();
    }
}
