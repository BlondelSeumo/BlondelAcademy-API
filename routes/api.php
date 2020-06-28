<?php

use App\Http\Controllers\ClassRoomController;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// No authentication for now
Route::group(['prefix' => 'v1'], function (Router $router) {
    $router->resource('classes', 'ClassRoomController');
    $router->resource('students', 'StudentController');
    $router->resource('videos', 'VideoController');
});
