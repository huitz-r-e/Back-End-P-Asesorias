<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Support\Facades\Validator;


class CategoriaController extends Controller
{
    //Con esta funcion protegemos nuestras rutas y solo podran acceder cuando estén autentificados
    public function __construct()
    {
        $this->middleware('auth');
    }

     //Trae todas las categorias
     public function getAllCategorias()
     {
         $categorias = Categoria::all();
 
         if ($categorias->isEmpty()) {
             return response()->json(['message' => 'No hay ningun registro en la DB'], 200);
         }
 
         return response()->json(['categorias' => $categorias], 200);
     }

    //Crear una categoria
    public function registrarCategoria(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required | string | max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $categoria = Categoria::create([
            'nombre' => $request->nombre
        ]);

        return response()->json(['categoria' => $categoria], 201);
    }

    //Borrar categoria por id de la BD
    public function deleteCategoryById($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['error' => 'Categoria no encontrada'], 404);
        }

        $categoria->delete();

        return response()->json(['message' => 'Categoria eliminada correctamente'], 200);
    }
}
