<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;




class AuthController extends Controller
{
    //Crear usuario
    public function formRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required | string | max:100',
            'lastname' => 'required | string | max:200',
            'mat' => 'required | string | max:10',
            'rol_id' => 'required',
            'edad' => 'required',
            'sexo' => 'required',
            'email' => 'required | string | email | unique:users',
            'password' => 'required | string | min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'edad' => $request->edad,
            'sexo' => $request->sexo,
            'mat' => $request->mat,
            'rol_id' => $request->rol_id,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json(['user' => $user], 201);
    }


    //Actualizar usuario
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'lastname' => 'required|string|max:200',
            'mat' => 'required|string|max:10',
            'rol_id' => 'required',
            'edad' => 'required',
            'sexo' => 'required',
            'email' => 'required|string|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8' // La contraseña es opcional en la actualización
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $user->update([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'edad' => $request->edad,
            'sexo' => $request->sexo,
            'mat' => $request->mat,
            'rol_id' => $request->rol_id,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password // Actualiza la contraseña solo si se proporciona
        ]);

        return response()->json(['user' => $user], 200);
    }

    //Me trae un usuario por id
    public function getUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json(['user' => $user], 200);
    }



    //Borrar usuario por id
    public function deleteUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
    }


    //Trae todos los usuarios
    public function getAllUsers()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No hay ningun registro en la DB'], 200);
        }

        return response()->json(['users' => $users], 200);
    }





    //Iniciar sesion con token
    public function startLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required | string | email',
            'password' => 'required | string'
        ]);

        if ($validator->fails()) {
            return response()->json(['Error' => $validator->errors()], 422);
        }

        $credentials = $request->only(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $user = $request->user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json(['Token' => $token, 'Usuario' => $user, 'AccessToken' => $token], 200);
    }
}
