<?php

namespace App\Http\Controllers;

use App\Models\Infoasesoria;
use App\Models\User;
use App\Models\Cv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class InfoAsesoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    //Actualizar asesoria
    public function actualizarAse(Request $request, $id)
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

        // Buscar la asesoría que se quiere actualizar
        $asesoria = InfoAsesoria::find($id);

        // Verificar si la asesoría existe y si pertenece al usuario autenticado
        if (!$asesoria || $asesoria->user_id !== $user->id) {
            return response()->json(['error' => 'Asesoría no encontrada'], 404);
        }

        // Validar los campos que se desean actualizar
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'desc' => 'required|string|max:200',
            'precio' => 'required|numeric|regex:/^\d{1,4}(\.\d{1,2})?$/|max:9999.99',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Actualizar la asesoría con los nuevos valores
        $asesoria->update([
            'nombre' => $request->nombre,
            'desc' => $request->desc,
            'precio' => $request->precio,
        ]);

        return response()->json(['asesoria' => $asesoria], 200);
    }



    //Agregar asesoria
    public function agregarAse(Request $request)
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

        // Verificar que el usuario tenga un registro en la tabla cvs con statuscv_id igual a 15
        $cv = Cv::where('user_id', $user->id)
            ->where('statuscv_id', 15)
            ->first();

        if (!$cv) {
            return response()->json(['error' => 'No cumple con los requisitos para registrar asesorías'], 403);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'categoria_id' => 'required',
            'desc' => 'required|string|max:200',
            'precio' => 'required|numeric|regex:/^\d{1,4}(\.\d{1,2})?$/|max:9999.99',
            'imgcurso' => 'required|image|max:2048',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Crear la asesoría con el user_id del usuario autenticado
        $rutaArchivoImg = $request->file('imgcurso')->store('public/imgscursos');
        $asesoria = InfoAsesoria::create([
            'nombre' => $request->nombre,
            'categoria_id' => $request->categoria_id,
            'desc' => $request->desc,
            'precio' => $request->precio,
            'user_id' => $user->id, // Asignamos el ID del usuario autenticado al campo user_id
            'imgcurso' => $rutaArchivoImg
        ]);

        return response()->json(['asesoria' => $asesoria], 201);
    }

    //Traer asesorias
    public function getAsesorias()
    {
        // Validar que el usuario esté autenticado antes de continuar
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // Obtener el usuario autenticado a partir del token de autorización en la cabecera
        $user = Auth::user();

        if ($user->rol_id === 1 || $user->rol_id === 3) {
            $asesorias = InfoAsesoria::with(['user', 'categoria']) // Cargar relación 'joinCategorias'
                ->where('active', 1)
                ->get();
        } else if ($user->rol_id === 2) {
            $asesorias = InfoAsesoria::with(['user', 'categoria']) // Cargar relación 'joinCategorias'
                ->where('user_id', $user->id)
                ->where('active', 1)
                ->get();
        } else {
            // Otros roles que no sean 1 o 2 no tienen acceso a esta función
            return response()->json(['error' => 'No autorizado'], 403);
        }

        return response()->json(['asesorias' => $asesorias], 200);
    }



    //Desactivar asesorias
    public function desactivarAsesoria($id)
    {
        // Validar que el usuario esté autenticado antes de continuar
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // Obtener el usuario autenticado a partir del token de autorización en la cabecera
        $user = Auth::user();

        // Verificar si el usuario tiene el rol_id igual a 1 o 2 (rol de administrador o usuario normal)
        if ($user->rol_id !== 1 && $user->rol_id !== 2) {
            // Si el rol_id no es 1 ni 2, el usuario no tiene permisos para desactivar asesorías
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Obtener la asesoría por su ID
        $asesoria = InfoAsesoria::find($id);

        // Verificar si la asesoría existe
        if (!$asesoria) {
            return response()->json(['error' => 'Asesoría no encontrada'], 404);
        }

        // Desactivar la asesoría cambiando el valor de active a 0
        $asesoria->active = 0;
        $asesoria->save();

        return response()->json(['message' => 'Asesoría desactivada exitosamente'], 200);
    }

    //Traer cursos por su categoria id
    public function cursosPorCategoria($categoria_id) {
        $asesorias = InfoAsesoria::where('categoria_id', $categoria_id)->get();
        return response()->json(['asesorias' => $asesorias]);
    }
    
}
