<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\todoController;
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

Route::get('/', [todoController::class,"showtask"]);

Route::post("/addtask",[todoController::class,"addtask"]);
Route::post("/taskcomplete",[todoController::class,"taskcomplete"]);
Route::post("/loaddata",[todoController::class,"loaddata"]);
Route::post("/taskedit",[todoController::class,"taskedit"]);
Route::post("/taskdelete",[todoController::class,"taskdelete"]);
