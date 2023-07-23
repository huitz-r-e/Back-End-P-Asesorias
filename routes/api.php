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
Route::put('/actualizar/{id}',[AuthController::class, 'update']); //Actualizar usuario
Route::get('/users/{id}',[AuthController::class, 'getUserById']); //Traer usuario por id
Route::get('users',[AuthController::class, 'getAllUsers']); //Traer todos los usuarios sin ninguna condicion
Route::delete('/users/{id}',[AuthController::class, 'deleteUserById']); //Borrar usuario por Id de la DB

Route::delete('/activaruser/{id}',[AuthController::class, 'activarUserById']); //Activar usuario por Id de la DB, cambiar a 1
Route::delete('/desactivaruser/{id}',[AuthController::class, 'desactivarUserById']); //Desactivar usuario por Id de la DB, cambiar a 0

Route::delete('activarusers',[AuthController::class, 'activarAllUsers']); //Activar todos los usuarios por Id de la DB sin importar rol, cambiar a 1
Route::delete('desactivarusers',[AuthController::class, 'desactivarAllUsers']); //Desactivar todos los usuarios por Id de la DB sin importar rol, cambiar a 0

Route::delete('desactivaradmins',[AuthController::class, 'desactivarAdmins']); //Desactivar todos los admins, cambiar active a 0
Route::delete('desactivarexperts',[AuthController::class, 'desactivarExperts']); //Desactivar todos los expertos, cambiar active a 0
Route::delete('desactivarstudents',[AuthController::class, 'desactivarStudents']); //Desactivar todos los estudiantes, cambiar active a 0

Route::delete('activaradmins',[AuthController::class, 'activarAdmins']); //Activar todos los admins, cambiar active a 1
Route::delete('activarexperts',[AuthController::class, 'activarExperts']); //Activar todos los expertos, cambiar active a 1
Route::delete('activarstudents',[AuthController::class, 'activarStudents']); //Activar todos los estudiantes, cambiar active a 1


Route::post('registerAdmin',[AuthController::class, 'addAdmin']); //Registrar admin sin necesidad de poner su rol_id
Route::post('registerExpert',[AuthController::class, 'addExpert']); //Registrar experto sin necesidad de poner su rol_id
Route::post('registerStudent',[AuthController::class, 'addStudent']); //Registrar estudiante sin necesidad de poner su rol_id
Route::get('admins',[AuthController::class, 'getAllAdmins']); //Traer todos los admins que esten activos
Route::get('experts',[AuthController::class, 'getAllExperts']); //Traer todos los expertos que esten activos
Route::get('students',[AuthController::class, 'getAllStudents']); //Traer todos los estudiantes que esten activos

