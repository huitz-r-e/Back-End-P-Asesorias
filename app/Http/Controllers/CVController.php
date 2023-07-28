<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Infoasesoria;
use App\Models\User;
use App\Models\Cv;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CVController extends Controller
{
    //Con esta funcion protegemos nuestras rutas y solo podran acceder cuando estén autentificados
    public function __construct()
    {
        $this->middleware('auth');
    }

    //Agregar asesoria
    public function agregarCv(Request $request)
    {
        // Validar que el usuario esté autenticado antes de continuar
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // Obtener el usuario autenticado a partir del token de autorización en la cabecera
        $user = Auth::user();

        // Verificar que el usuario tenga el rol_id igual a 2 (rol de usuario normal)
        if ($user->rol_id !== 2) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'razon' => 'required|string|max:200',
            'rutaCv' => 'required|mimes:pdf|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Subir el cv con el user_id del usuario autenticado
        $rutaArchivo = $request->file('rutaCv')->store('public/pdf');
        $micv = Cv::create([
            'razon' => $request->razon,
            'user_id' => $user->id,
            'rutaCv' => $rutaArchivo // Use 'rutaCv' instead of 'rutaPdfVoucherE'
        ]);

        return response()->json(['cv' => $micv], 201);
    }


    //Actualizar cv
    public function actualizarCv($cvId)
    {
        // Validar que el usuario esté autenticado antes de continuar
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // Obtener el usuario autenticado a partir del token de autorización en la cabecera
        $user = Auth::user();

        // Verificar que el usuario tenga el rol_id igual a 1 (rol de usuario normal)
        if ($user->rol_id !== 1) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Buscar el CV existente por su ID
        $cv = Cv::find($cvId);

        // Verificar si el CV existe
        if (!$cv) {
            return response()->json(['error' => 'CV no encontrado en DB'], 404);
        }

        // Actualizar el campo statuscv_id con el nuevo valor (15)
        $cv->statuscv_id = 15;
        $cv->save();

        return response()->json(['message' => 'CV actualizado correctamente'], 200);
    }



    //Eliminar Cv
    public function eliminarCv($cvId)
    {
        // Validar que el usuario esté autenticado antes de continuar
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // Obtener el usuario autenticado a partir del token de autorización en la cabecera
        $user = Auth::user();

        // Verificar que el usuario tenga el rol_id igual a 1 (rol de usuario normal)
        if ($user->rol_id !== 1) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Buscar el CV existente por su ID
        $cv = Cv::find($cvId);

        // Verificar si el CV existe
        if (!$cv) {
            return response()->json(['error' => 'CV no encontrado en DB'], 404);
        }

        // Eliminar el archivo PDF asociado al CV, si existe
        if (Storage::exists($cv->rutaCv)) {
            Storage::delete($cv->rutaCv);
        }

        // Eliminar el registro del CV de la base de datos
        $cv->delete();

        return response()->json(['message' => 'CV eliminado correctamente'], 200);
    }

    //Traer los cvs para los admins
    public function getCvs()
    {
        // Validar que el usuario esté autenticado antes de continuar
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // Obtener el usuario autenticado a partir del token de autorización en la cabecera
        $user = Auth::user();

        if ($user->rol_id !== 1) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Buscar los CV existentes y cargar la relación con el usuario
        $cvs = Cv::with(['user', 'status'])->get();

        // Verificar que existan CVs
        if ($cvs->isEmpty()) {
            return response()->json(['error' => 'No hay ningún registro de CV'], 404);
        }

        return response()->json(['cvs' => $cvs], 200);
    }
}
