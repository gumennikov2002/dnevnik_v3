<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabinetController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\CrudController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClassroomsController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SidebarController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\UsersController;
use App\Models\Schedule;
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
    Route::get('auth', [AuthController::class, 'index'])->name('auth');

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    });

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/logout', [ProfileController::class, 'logout'])->name('profile.logout');

    Route::get('users_crud', [UsersController::class, 'index'])->name('users');
    Route::post('users_crud/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('users_crud/delete', [UsersController::class, 'delete'])->name('users.delete');
    Route::post('users_crud/get_fields', [UsersController::class, 'get_fields'])->name('users.get_fields');
    Route::post('users_crud/update', [UsersController::class, 'update'])->name('users.update');

    Route::get('classrooms_crud', [ClassroomsController::class, 'index'])->name('classrooms');
    Route::post('classrooms_crud/create', [ClassroomsController::class, 'create'])->name('classrooms.create');
    Route::post('classrooms_crud/delete', [ClassroomsController::class, 'delete'])->name('classrooms.delete');
    Route::post('classrooms_crud/get_fields', [ClassroomsController::class, 'get_fields'])->name('classrooms.get_fields');
    Route::post('classrooms_crud/update', [ClassroomsController::class, 'update'])->name('classrooms.update');

    Route::get('class_crud', [ClassesController::class, 'index'])->name('class');
    Route::post('class_crud/create', [ClassesController::class, 'create'])->name('class.create');
    Route::post('class_crud/delete', [ClassesController::class, 'delete'])->name('class.delete');
    Route::post('class_crud/get_fields', [ClassesController::class, 'get_fields'])->name('class.get_fields');
    Route::post('class_crud/update', [ClassesController::class, 'update'])->name('class.update');

    Route::get('subjects_crud', [SubjectsController::class, 'index'])->name('subjects');
    Route::post('subjects_crud/create', [SubjectsController::class, 'create'])->name('subjects.create');
    Route::post('subjects_crud/delete', [SubjectsController::class, 'delete'])->name('subjects.delete');
    Route::post('subjects_crud/get_fields', [SubjectsController::class, 'get_fields'])->name('subjects.get_fields');
    Route::post('subjects_crud/get_subjects', [SubjectsController::class, 'get_subjects'])->name('subjects.get_subjects');
    Route::post('subjects_crud/update', [SubjectsController::class, 'update'])->name('subjects.update');
    
    Route::get('cabinets_crud', [CabinetController::class, 'index'])->name('cabinets');
    Route::post('cabinets_crud/create', [CabinetController::class, 'create'])->name('cabinets.create');
    Route::post('cabinets_crud/delete', [CabinetController::class, 'delete'])->name('cabinets.delete');
    Route::post('cabinets_crud/get_fields', [CabinetController::class, 'get_fields'])->name('cabinets.get_fields');
    Route::post('cabinets_crud/update', [CabinetController::class, 'update'])->name('cabinets.update');

    Route::get('schedule', [ScheduleController::class, 'index'])->name('schedule');
    Route::post('schedule/get_modal_fields', [ScheduleController::class, 'get_modal_fields'])->name('schedule.get_modal_fields');
});
