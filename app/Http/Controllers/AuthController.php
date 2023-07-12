<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;




class AuthController extends Controller
{
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

        // return response()->json(['Token' => $token, 'Usuario' => $user], 200);
        return response()->json(['Token' => $token, 'Usuario' => $user, 'AccessToken' => $token], 200);
    }
}
