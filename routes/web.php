<?php

use App\Http\Controllers\CabinetController;
use App\Http\Controllers\CrudController;
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

Route::get('/dashboard', function () {
    return view('dashboard.index');
});

Route::get('users_crud', [UsersController::class, 'index']);
Route::post('users_crud/create', [UsersController::class, 'create']);
Route::post('users_crud/delete', [UsersController::class, 'delete']);
Route::post('users_crud/get_fields', [UsersController::class, 'get_fields']);
Route::post('users_crud/update', [UsersController::class, 'update']);
Route::post('users_crud/search', [UsersController::class, 'search']);

Route::get('cabinets_crud', [CabinetController::class, 'index']);
Route::post('cabinets_crud/create', [CabinetController::class, 'create']);
Route::post('cabinets_crud/delete', [CabinetController::class, 'delete']);
Route::post('cabinets_crud/get_fields', [CabinetController::class, 'get_fields']);
Route::post('cabinets_crud/update', [CabinetController::class, 'update']);
Route::post('cabinets_crud/search', [CabinetController::class, 'search']);
