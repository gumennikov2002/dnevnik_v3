<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabinetController;
use App\Http\Controllers\CrudController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClassroomsController;
use App\Http\Controllers\SidebarController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::post('auth/check', [AuthController::class, 'check']);
Route::post('sidebar/load', [SidebarController::class, 'load']);

Route::group(['middleware' => 'AuthCheck'], function() {
    Route::get('auth', [AuthController::class, 'index']);

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    });

    Route::get('/profile', [ProfileController::class, 'index']);
    Route::get('/profile/logout', [ProfileController::class, 'logout']);

    Route::get('users_crud', [UsersController::class, 'index']);
    Route::post('users_crud/create', [UsersController::class, 'create']);
    Route::post('users_crud/delete', [UsersController::class, 'delete']);
    Route::post('users_crud/get_fields', [UsersController::class, 'get_fields']);
    Route::post('users_crud/update', [UsersController::class, 'update']);
    Route::post('users_crud/search', [UsersController::class, 'search']);

    Route::get('classrooms_crud', [ClassroomsController::class, 'index']);
    Route::post('classrooms_crud/create', [ClassroomsController::class, 'create']);
    Route::post('classrooms_crud/delete', [ClassroomsController::class, 'delete']);
    Route::post('classrooms_crud/get_fields', [ClassroomsController::class, 'get_fields']);
    Route::post('classrooms_crud/update', [ClassroomsController::class, 'update']);
    Route::post('classrooms_crud/search', [ClassroomsController::class, 'search']);

    Route::get('subjects_crud', [SubjectsController::class, 'index']);
    Route::post('subjects_crud/create', [SubjectsController::class, 'create']);
    Route::post('subjects_crud/delete', [SubjectsController::class, 'delete']);
    Route::post('subjects_crud/get_fields', [SubjectsController::class, 'get_fields']);
    Route::post('subjects_crud/update', [SubjectsController::class, 'update']);
    Route::post('subjects_crud/search', [SubjectsController::class, 'search']);
    
    Route::get('cabinets_crud', [CabinetController::class, 'index']);
    Route::post('cabinets_crud/create', [CabinetController::class, 'create']);
    Route::post('cabinets_crud/delete', [CabinetController::class, 'delete']);
    Route::post('cabinets_crud/get_fields', [CabinetController::class, 'get_fields']);
    Route::post('cabinets_crud/update', [CabinetController::class, 'update']);
    Route::post('cabinets_crud/search', [CabinetController::class, 'search']);
});
