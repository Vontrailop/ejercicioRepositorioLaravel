<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\AutorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

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
Route::prefix('book')->group(function(){
    Route::get('index', [BookController::class, 'index']);
    Route::post('store', [BookController::class, 'store']);
    Route::put('update/{id}', [BookController::class, 'update']);
    Route::get('show/{id}', [BookController::class, 'show']);
    Route::delete('delete/{id}', [BookController::class, 'delete']);
});

Route::prefix('author')->group(function(){
    Route::get('index', [AutorController::class, 'index']);
    Route::post('store', [AutorController::class, 'store']);
    Route::put('update/{id}', [AutorController::class, 'update']);
    Route::get('show/{id}', [AutorController::class, 'show']);
    Route::delete('delete/{id}', [AutorController::class, 'delete']);
});



//Other code...

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ["auth:sanctum"]], function () {
    Route::get('userProfile', [AuthController::class, 'userProfile']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::put('changePassword', [AuthController::class, 'changePassword']);
});
