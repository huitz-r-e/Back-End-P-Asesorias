<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CVController;
use App\Http\Controllers\InfoAsesoriaController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ReunionController;
use App\Models\InfoAsesoria;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();

// });



Route::middleware(['auth:sanctum'])->group(function() {
    //Routes for user
Route::get('logout', [AuthController::class, 'endLogin']); //Cerrar sesion
Route::put('actualizar', [AuthController::class, 'update']); //Actualizar usuario autenticado donde su id se agrega automaticamente
Route::put('/actualizarusuario/{id}', [AuthController::class, 'updateById']); //Actualizar usuario por id
Route::get('/users/{id}', [AuthController::class, 'getUserById']); //Traer usuario por id
Route::get('users', [AuthController::class, 'getAllUsers']); //Traer todos los usuarios sin ninguna condicion
Route::delete('/users/{id}', [AuthController::class, 'deleteUserById']); //Borrar usuario por Id de la DB

Route::delete('/activaruser/{id}', [AuthController::class, 'activarUserById']); //Activar usuario por Id de la DB, cambiar a 1
Route::delete('/desactivaruser/{id}', [AuthController::class, 'desactivarUserById']); //Desactivar usuario por Id de la DB, cambiar a 0

Route::delete('activarusers', [AuthController::class, 'activarAllUsers']); //Activar todos los usuarios por Id de la DB sin importar rol, cambiar a 1
Route::delete('desactivarusers', [AuthController::class, 'desactivarAllUsers']); //Desactivar todos los usuarios por Id de la DB sin importar rol, cambiar a 0

Route::delete('desactivaradmins', [AuthController::class, 'desactivarAdmins']); //Desactivar todos los admins, cambiar active a 0
Route::delete('desactivarexperts', [AuthController::class, 'desactivarExperts']); //Desactivar todos los expertos, cambiar active a 0
Route::delete('desactivarstudents', [AuthController::class, 'desactivarStudents']); //Desactivar todos los estudiantes, cambiar active a 0

Route::delete('activaradmins', [AuthController::class, 'activarAdmins']); //Activar todos los admins, cambiar active a 1
Route::delete('activarexperts', [AuthController::class, 'activarExperts']); //Activar todos los expertos, cambiar active a 1
Route::delete('activarstudents', [AuthController::class, 'activarStudents']); //Activar todos los estudiantes, cambiar active a 1


Route::post('registerAdmin', [AuthController::class, 'addAdmin']); //Registrar admin sin necesidad de poner su rol_id
Route::post('registerExpert', [AuthController::class, 'addExpert']); //Registrar experto sin necesidad de poner su rol_id
Route::post('registerStudent', [AuthController::class, 'addStudent']); //Registrar estudiante sin necesidad de poner su rol_id
Route::get('admins', [AuthController::class, 'getAllAdmins']); //Traer todos los admins que esten activos
Route::get('experts', [AuthController::class, 'getAllExperts']); //Traer todos los expertos que esten activos
Route::get('students', [AuthController::class, 'getAllStudents']); //Traer todos los estudiantes que esten activos

//Usuarios desactivados
Route::get('adminsdesactivados', [AuthController::class, 'getAllAdminsDesactivados']); //Traer todos los admins que esten desactivados
Route::get('expertsdesactivados', [AuthController::class, 'getAllExpertsDesactivados']); //Traer todos los expertos que esten desactivados
Route::get('studentsdesactivados', [AuthController::class, 'getAllStudentsDesactivados']); //Traer todos los estudiantes que esten desactivados

//Rutas de asesorias de parte del experto
Route::post('registrarA', [InfoAsesoriaController::class, 'agregarAse']); //Registrar una asesoria por el experto y agrega el user_id automaticamente
Route::put('/actualizarA/{id}', [InfoAsesoriaController::class, 'actualizarAse']); //Actualizar una asesoria por el experto y agrega el user_id automaticamente
Route::get('asesorias', [InfoAsesoriaController::class, 'getAsesorias']); //Me trae las asesorias de cada instructor para el admin o experto
Route::delete('/asesorias/{id}', [InfoAsesoriaController::class, 'desactivarAsesoria']); //Me desactiva la asesoria por id


//Rutas para el estudiante y experto
Route::post('/pedirAsesoria/{id}', [RegistroController::class, 'altaAsesoria']); //Pedir asesoria por id de asesoria para el estudiante
Route::get('verAsesorias', [RegistroController::class, 'getRegistros']); //Trae info de asesorias
Route::get('/verAsesoria/{id}', [RegistroController::class, 'getRegistroById']); //Trae info de asesoria por su id
Route::put('/actualizarAsesoria/{id}', [RegistroController::class, 'actualizarAsesoria']); //Actualizar info de asesoria
Route::delete('/eliminarAsesoriaEstudiante/{id}', [RegistroController::class, 'eliminarAsesoria']); //Eliminar registro de estudiante a asesoria
Route::delete('/eliminarAsesoriaExperto/{id}', [RegistroController::class, 'deleteRegistroById']); //Eliminar asesoria por id de parte del administrador
Route::get('/asesoriaporcategoria/{id}', [InfoAsesoriaController::class, 'cursosPorCategoria']); //Traer asesorias por su categoria por medio de su id

//Rutas para el CV
Route::post('subirCv', [CVController::class, 'agregarCv']); //Subir Cv
Route::put('/aprobarCv/{id}', [CVController::class, 'actualizarCv']); //Aprobar Cv
Route::put('/rechazarCv/{id}', [CVController::class, 'rechazarCv']); //Rechazar Cv
Route::delete('/eliminarCv/{id}', [CVController::class, 'eliminarCv']); //Eliminar Cv
Route::get('cvsProceso', [CVController::class, 'getCvs']); //Traer Cvs para el admin en PROCESO
Route::get('cvsAprobados', [CVController::class, 'getCvsAprobados']); //Traer Cvs para el admin en APROBADOS
Route::get('cvsRechazados', [CVController::class, 'getCvsRechazados']); //Traer Cvs para el admin en RECHAZADOS
Route::get('infocv', [CVController::class, 'getCvUsuarioActual']); //Trae info de CV del experto que haya iniciado sesion


//Todo lo que tenga que ver con categorias
Route::get('categorias', [CategoriaController::class, 'getAllCategorias']); //Traer todas las categorias
Route::post('registrarcategoria', [CategoriaController::class, 'registrarCategoria']); //Crear una categoria
Route::delete('/categoria/{id}', [CategoriaController::class, 'deleteCategoryById']); //Eliminar una categoria por id

//Todo lo que tenga que ver con reuniones entre experto y estudiante
Route::post('/registrarcita/{id}', [ReunionController::class, 'agregarCitaPorId']); //Crear una cita para el estudiante, dada por el experto por medio del id del registro
Route::put('/confirmarcita/{id}', [ReunionController::class, 'confirmarCitaPorId']); //Confirmar cita por parte del estudiante por id

});

//Rutas publicas que no requieren estar autenticado
Route::post('login', [AuthController::class, 'startLogin']); //Iniciar sesion
Route::post('register', [AuthController::class, 'formRegister']); //Registrar usuario


