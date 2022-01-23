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

Route::get('users', [UsersController::class, 'index']);
Route::get('cabinets', [CabinetController::class, 'index']);
Route::post('cabinets/create', [CabinetController::class, 'create']);
Route::post('users/create', [UsersController::class, 'create']);
Route::post('users/delete', [UsersController::class, 'delete']);
Route::post('cabinets/delete', [CabinetController::class, 'delete']);
