<?php

namespace App\Http\Controllers;

use App\Models\Infoasesoria;
use App\Models\Registro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegistroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //Darse de alta a una asesoria por el estudiante pero de manera manual
    public function altaAsesoriaPeso(Request $request)
    {
        // Validar que el usuario esté autenticado antes de continuar
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // Obtener el usuario autenticado a partir del token de autorización en la cabecera
        $user = Auth::user();

        // Verificar que el usuario tenga el rol_id igual a 3 (rol de usuario normal)
        if ($user->rol_id !== 3) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'infoa_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verificar si el usuario ya tiene una asesoría con la misma infoa_id
        $existingAsesoria = Registro::where('user_id', $user->id)
            ->where('infoa_id', $request->infoa_id)
            ->first();

        if ($existingAsesoria) {
            return response()->json(['error' => 'Ya tienes una asesoría registrada con esta infoa_id'], 400);
        } else {
            return response()->json(['error' => 'Esa asesoria no existe en la DB'], 404);
        }

        // Crear la asesoría con el user_id del usuario autenticado
        $asesoria = Registro::create([
            'user_id' => $user->id, // Asignamos el ID del usuario autenticado al campo user_id
            'infoa_id' => $request->infoa_id,
        ]);

        return response()->json(['asesoria' => $asesoria], 201);
    }

    //Estudiante se da de alta a una asesoria por medio del id en la URL
    public function altaAsesoria(Request $request, $id)
    {
        // Validar que el usuario esté autenticado antes de continuar
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // Obtener el usuario autenticado a partir del token de autorización en la cabecera
        $user = Auth::user();

        // Verificar que el usuario tenga el rol_id igual a 3 (rol de usuario normal)
        if ($user->rol_id !== 3) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Verificar si el usuario ya tiene una asesoría con el mismo id
        $existingAsesoria = Registro::where('user_id', $user->id)
            // ->where('id', $id)
            ->where('infoa_id', $request->id) //Este es el mismo id de la asesoria
            ->first();

        if ($existingAsesoria) {
            return response()->json(['error' => 'Ya tienes una asesoría registrada con este id'], 400);
        }

        // Buscar la asesoría existente por su ID
        $asesoria = Infoasesoria::find($id);

        // Verificar si se encontró la asesoría
        if (!$asesoria) {
            return response()->json(['error' => 'Asesoría no encontrada en la DB'], 404);
        }

        // Asignar la asesoría al usuario
        $registro = Registro::create([
            'user_id' => $user->id,
            'infoa_id' => $id,
        ]);

        return response()->json(['registro' => $registro], 201);
    }






    //Me trae los registros
    public function getRegistros()
    {
        // Validar que el usuario esté autenticado antes de continuar
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // Obtener el usuario autenticado a partir del token de autorización en la cabecera
        $user = Auth::user();

        // Verificar el rol del usuario
        if ($user->rol_id === 3) {
            // Si el rol_id es igual a 3 (rol de estudiante), se muestran todas las asesorías del usuario
            $asesorias = Registro::with(['user', 'infoasesoria', 'infoasesoria.user'])->where('user_id', $user->id)->get();
            $registros = [];
        } else if ($user->rol_id === 2) {
            // Si el rol_id es igual a 2 (rol de usuario normal), se muestran solo las asesorías del usuario que tengan active=1
            $asesorias = InfoAsesoria::with('user')->where('user_id', $user->id)->where('active', 1)->get();

            // Verificar si el arreglo de asesorías está vacío y asignar un arreglo vacío a $registros en ese caso
            if ($asesorias->isEmpty()) {
                $registros = [];
            } else {
                // Obtener los ids de las asesorías del usuario con rol_id = 2
                $asesoriaIds = $asesorias->pluck('id')->all();
                // Obtener los registros del usuario con rol_id = 2 que coincidan con las asesorías obtenidas
                $registros = Registro::with('user')->whereIn('infoa_id', $asesoriaIds)->get();
            }
        } else {
            // Otros roles que no sean 1 o 2 no tienen acceso a esta función
            return response()->json(['error' => 'No autorizado'], 403);
        }

        return response()->json(['asesorias' => $asesorias, 'registros' => $registros], 200);
    }

    //Me trae los datos de una asesoria y sus estudiantes incritos
    public function getRegistroById(Request $request, $id)
    {
        // Validar que el usuario esté autenticado antes de continuar
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // Obtener el usuario autenticado a partir del token de autorización en la cabecera
        $user = Auth::user();

        // Verificar el rol del usuario
        if ($user->rol_id === 2) {

            // Obtener la asesoría por su ID y perteneciente al usuario actual
            $asesoria = InfoAsesoria::with('user')->where('user_id', $user->id)
                ->where('active', 1)
                ->find($id);

            if (!$asesoria) {
                return response()->json(['error' => 'No existe esta asesoria en tus registros'], 404);
            }

            // Obtener los registros asociados a la asesoría con el ID proporcionado
            $registros = Registro::with('user')->where('infoa_id', $asesoria->id)->get();
        } else {
            // Otros roles que no sean 3 o 1 no tienen acceso a esta función
            return response()->json(['error' => 'No autorizado'], 403);
        }

        return response()->json(['asesoria' => $asesoria, 'registros' => $registros], 200);
    }



    public function actualizarAsesoria(Request $request, $id)
    {
        // Validar que el usuario esté autenticado antes de continuar
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // Obtener el usuario autenticado a partir del token de autorización en la cabecera
        $user = Auth::user();

        // Verificar que el usuario tenga el rol_id igual a 3 (rol de usuario normal)
        if ($user->rol_id !== 3) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'infoa_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Buscar el registro que se desea actualizar
        $registro = Registro::where('id', $id)->where('user_id', $user->id)->first();

        // Verificar si el registro existe y pertenece al usuario autenticado
        if (!$registro) {
            return response()->json(['error' => 'Registro no encontrado o no autorizado'], 404);
        }

        // Actualizar la información del registro
        $registro->update([
            'infoa_id' => $request->infoa_id,
            // Agrega aquí otros campos que desees actualizar
        ]);

        return response()->json(['asesoria' => $registro], 200);
    }





    public function eliminarAsesoria($id)
    {
        // Validar que el usuario esté autenticado antes de continuar
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // Obtener el usuario autenticado a partir del token de autorización en la cabecera
        $user = Auth::user();

        // Verificar que el usuario tenga el rol_id igual a 3 (rol de estudiante)
        if ($user->rol_id !== 3) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Buscar el registro que se desea eliminar
        $registro = Registro::where('id', $id)->where('user_id', $user->id)->first();

        // Verificar si el registro existe y pertenece al usuario autenticado
        if (!$registro) {
            return response()->json(['error' => 'Registro no encontrado o no autorizado'], 404);
        }

        // Eliminar el registro
        $registro->delete();

        return response()->json(['message' => 'Registro eliminado exitosamente'], 200);
    }


    public function deleteRegistroById(Request $request, $id)
    {
        // Validar que el usuario esté autenticado antes de continuar
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // Obtener el usuario autenticado a partir del token de autorización en la cabecera
        $user = Auth::user();

        // Verificar el rol del usuario
        if ($user->rol_id === 2) {
            // Obtener la asesoría o registro por su ID y perteneciente al usuario actual
            $asesoria = InfoAsesoria::where('user_id', $user->id)
                ->where('active', 1)
                ->find($id);

            if (!$asesoria) {
                return response()->json(['error' => 'No existe esta asesoria o registro en tus registros'], 404);
            }

            $asesoria->delete();

            return response()->json(['message' => 'Eliminación exitosa'], 200);
        } else {
            // Otros roles que no sean 3 o 1 no tienen acceso a esta función
            return response()->json(['error' => 'No autorizado'], 403);
        }
    }
}
