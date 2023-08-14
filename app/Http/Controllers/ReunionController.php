<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Reunion;

class ReunionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //Agendar cita por id por medio de la url
    public function agregarCitaPorId(Request $request, $registro_id)
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
            'tema' => 'required|string|max:200',
            'urlmeet' => 'required|string|max:200',
            'fecha' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        // Verificar si el registro existe en la base de datos
        $registro = Registro::find($registro_id);

        if (!$registro) {
            return response()->json(['error' => 'No existe el id en la BD'], 404);
        }

        $micita = Reunion::create([
            'tema' => $request->tema,
            'urlmeet' => $request->urlmeet,
            'registro_id' => $registro_id, // Utilizar el valor de la URL
            'expert_id' => $user->id,
            'fecha' => $request->fecha,
        ]);

        return response()->json(['data' => $micita], 201);
    }

    //Confirmar cita
    public function confirmarCitaPorId(Request $request, $registro_id)
    {
        // Validar que el usuario esté autenticado antes de continuar
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // Obtener el usuario autenticado a partir del token de autorización en la cabecera
        $user = Auth::user();

        // Verificar que el usuario tenga el rol_id igual a 2 (rol de usuario normal)
        if ($user->rol_id !== 3) {
            return response()->json(['error' => 'No autorizado'], 403);
        }
        // Verificar si el registro existe en la base de datos
        $micita = Reunion::find($registro_id);

        if (!$micita) {
            return response()->json(['error' => 'No existe el id en la BD'], 404);
        }
        // Actualizar el campo 'confirmacion' en el registro
        $micita->update([
            'confirmacion' => true,
        ]);


        // return response()->json(['data' => $micita], 201);
        return response()->json(['message' => 'Confirmación actualizada correctamente'], 200);
    }

    //Traer todas las reuniones para experto autenticado
    public function obtenerTodasLasReuniones(Request $request)
    {
        // Validar que el usuario esté autenticado antes de continuar
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // Obtener el usuario autenticado a partir del token de autorización en la cabecera
        $user = Auth::user();

        // Verificar el rol del usuario
        if ($user->rol_id === 2) {
            // Obtener todas las reuniones donde el expert_id coincida con el id del usuario autenticado
            $citas = Reunion::with('registro')->where('expert_id', $user->id)->get();
        } else {
            // Otros roles que no sean 3 o 1 no tienen acceso a esta función
            return response()->json(['error' => 'No autorizado'], 403);
        }

        return response()->json($citas, 200);
    }

    //Traer reuniones para estudiante autenticado
    public function obtenerCitasEstudiante(Request $request)
{
    // Validar que el usuario esté autenticado antes de continuar
    if (!Auth::check()) {
        return response()->json(['error' => 'No autorizado'], 401);
    }

    // Obtener el usuario autenticado a partir del token de autorización en la cabecera
    $user = Auth::user();

    // Verificar el rol del usuario
    if ($user->rol_id === 3) {
        // Obtener todas las reuniones relacionadas con el usuario autenticado
        $citas = Reunion::with(['registro' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->whereHas('registro', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->get();
    } else {
        // Otros roles que no sean 3 o 1 no tienen acceso a esta función
        return response()->json(['error' => 'No autorizado'], 403);
    }

    return response()->json($citas, 200);
}

}
