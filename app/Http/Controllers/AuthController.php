<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;




class AuthController extends Controller
{
    //Crear usuario pidiendo su rol
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



    //Borrar usuario por id de la BD
    public function deleteUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
    }

    //Desactivar todos los usuarios sin importar rol
    public function desactivarAllUsers()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No hay ningun usuario en la DB'], 200);
        }

        foreach ($users as $user) {
            $user->active = 0;
            $user->save();
        }

        return response()->json(['message' => 'Todos los usuarios han sido desactivados correctamente'], 200);
    }

    //Activar todos los usuarios sin importar rol
    public function activarAllUsers()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No hay ningun usuario en la DB'], 200);
        }

        foreach ($users as $user) {
            $user->active = 1;
            $user->save();
        }

        return response()->json(['message' => 'Todos los usuarios han sido activados correctamente'], 200);
    }


    // Desactivar administradores (usuarios que tengan role=1)
    public function desactivarAdmins()
    {
        $users = User::where('rol_id', 1)->get();

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No hay ningun usuario con rol_id igual a 1 en la DB'], 200);
        }

        foreach ($users as $user) {
            $user->active = 0;
            $user->save();
        }

        return response()->json(['message' => 'Todos los usuarios con rol_id igual a 1 han sido desactivados correctamente'], 200);
    }

    // Desactivar administradores (usuarios que tengan role=2)
    public function desactivarExperts()
    {
        $users = User::where('rol_id', 2)->get();

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No hay ningun usuario con rol_id igual a 2 en la DB'], 200);
        }

        foreach ($users as $user) {
            $user->active = 0;
            $user->save();
        }

        return response()->json(['message' => 'Todos los usuarios con rol_id igual a 2 han sido desactivados correctamente'], 200);
    }

    // Desactivar estudiantes (usuarios que tengan role=3)
    public function desactivarStudents()
    {
        $users = User::where('rol_id', 3)->get();

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No hay ningun usuario con rol_id igual a 3 en la DB'], 200);
        }

        foreach ($users as $user) {
            $user->active = 0;
            $user->save();
        }

        return response()->json(['message' => 'Todos los usuarios con rol_id igual a 3 han sido desactivados correctamente'], 200);
    }


    // Activar administradores (usuarios que tengan role=1)
    public function activarAdmins()
    {
        $users = User::where('rol_id', 1)->get();

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No hay ningun usuario con rol_id igual a 1 en la DB'], 200);
        }

        foreach ($users as $user) {
            $user->active = 1;
            $user->save();
        }

        return response()->json(['message' => 'Todos los usuarios con rol_id igual a 1 han sido activados correctamente'], 200);
    }


    // Activar expertos (usuarios que tengan role=2)
    public function activarExperts()
    {
        $users = User::where('rol_id', 2)->get();

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No hay ningun usuario con rol_id igual a 2 en la DB'], 200);
        }

        foreach ($users as $user) {
            $user->active = 1;
            $user->save();
        }

        return response()->json(['message' => 'Todos los usuarios con rol_id igual a 2 han sido activados correctamente'], 200);
    }


    // Activar estudiantes (usuarios que tengan role=3)
    public function activarStudents()
    {
        $users = User::where('rol_id', 3)->get();

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No hay ningun usuario con rol_id igual a 3 en la DB'], 200);
        }

        foreach ($users as $user) {
            $user->active = 1;
            $user->save();
        }

        return response()->json(['message' => 'Todos los usuarios con rol_id igual a 3 han sido activados correctamente'], 200);
    }


    //Desactivar el usuario por ID
    public function desactivarUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $user->active = 0;
        $user->save();

        return response()->json(['message' => 'Usuario desactivado correctamente'], 200);
    }

    //Activar el usuario por ID
    public function activarUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $user->active = 1;
        $user->save();

        return response()->json(['message' => 'Usuario activado correctamente'], 200);
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



    //Me trae todos los administradores
    public function getAllAdmins()
    {
        $users = User::where('rol_id', 1)
            ->where(
                'active',
                1
            )->get();
        if ($users->isEmpty()) {
            return response()->json(['message' => 'No hay ningun registro en la DB'], 200);
        }

        return response()->json(['users' => $users], 200);
    }

    //Me trae todos los expertos
    public function getAllExperts()
    {
        $users = User::where('rol_id', 2)
            ->where(
                'active',
                1
            )->get();
        if ($users->isEmpty()) {
            return response()->json(['message' => 'No hay ningun registro en la DB'], 200);
        }

        return response()->json(['users' => $users], 200);
    }


    //Me trae todos los estudiantes
    public function getAllStudents()
    {
        $users = User::where('rol_id', 3)
            ->where(
                'active',
                1
            )->get();
        if ($users->isEmpty()) {
            return response()->json(['message' => 'No hay ningún registro en la DB'], 200);
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


    //Cerrar sesion

    public function endLogin(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully'
        ], 200);
    }

    //Crear un administrador y que agregue su rol por defecto
    public function addAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'lastname' => 'required|string|max:200',
            'mat' => 'required|string|max:10',
            'edad' => 'required',
            'sexo' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8'
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
            'rol_id' => 1,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json(['user' => $user], 201);
    }


    //Crear un experto y que agregue su rol por defecto
    public function addExpert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'lastname' => 'required|string|max:200',
            'mat' => 'required|string|max:10',
            'edad' => 'required',
            'sexo' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8'
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
            'rol_id' => 2,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json(['user' => $user], 201);
    }


    //Crear un estudiante y que agregue su rol por defecto
    public function addStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'lastname' => 'required|string|max:200',
            'mat' => 'required|string|max:10',
            'edad' => 'required',
            'sexo' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8'
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
            'rol_id' => 3,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json(['user' => $user], 201);
    }
}
