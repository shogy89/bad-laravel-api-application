<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
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

Route::POST('register', [AuthController::class, 'register']);
Route::POST('login', [AuthController::class, 'login']);

Route::group([
    'middleware' => ['bearerauth'],
  ], function () {
      Route::POST('upload-file', [UploadController::class, 'store']);
});