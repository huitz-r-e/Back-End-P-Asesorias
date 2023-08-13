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


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
