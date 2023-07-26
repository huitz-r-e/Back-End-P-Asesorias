<?php

namespace App\Http\Controllers;

use App\Models\Infoasesoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class InfoAsesoriaController extends Controller
{


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




    public function addAsesoria(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'desc' => 'required|string|max:200',
            'precio' => 'required|numeric|regex:/^\d{1,4}(\.\d{1,2})?$/|max:9999.99',
            'user_id' => 'required'



        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = InfoAsesoria::create([
            'nombre' => $request->nombre,
            'desc' => $request->desc,
            'precio' => $request->precio,
            'user_id' => $request->user_id,
        ]);

        return response()->json(['user' => $user], 201);
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

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'desc' => 'required|string|max:200',
            'precio' => 'required|numeric|regex:/^\d{1,4}(\.\d{1,2})?$/|max:9999.99',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Crear la asesoría con el user_id del usuario autenticado
        $asesoria = InfoAsesoria::create([
            'nombre' => $request->nombre,
            'desc' => $request->desc,
            'precio' => $request->precio,
            'user_id' => $user->id, // Asignamos el ID del usuario autenticado al campo user_id
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

        // Verificar el rol del usuario
        if ($user->rol_id === 1 || $user->rol_id === 3) {
            // Si el rol_id es igual a 1 o 3 (rol de administrador y estudiante), se muestran todas las asesorías que tengan active=1
            $asesorias = InfoAsesoria::where('active', 1)->get();
        } else if ($user->rol_id === 2) {
            // Si el rol_id es igual a 2 (rol de usuario normal), se muestran solo las asesorías del usuario que tengan active=1
            $asesorias = InfoAsesoria::where('user_id', $user->id)->where('active', 1)->get();
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






    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
