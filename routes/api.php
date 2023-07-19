<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//Routes for user
Route::post('login',[AuthController::class, 'startLogin']); //Iniciar sesion
Route::post('register',[AuthController::class, 'formRegister']); //Registrar usuario
Route::put('/actualizar/{id}',[AuthController::class, 'update']); //Actualizar usuarios
Route::get('/users/{id}',[AuthController::class, 'getUserById']); //Traer usuario por id
Route::get('users',[AuthController::class, 'getAllUsers']); //Traer todos los usuarios 
Route::delete('/users/{id}',[AuthController::class, 'deleteUserById']); //Borrar usuario por Id
