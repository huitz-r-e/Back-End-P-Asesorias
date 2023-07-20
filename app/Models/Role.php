<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['rol', 'descripcion'];


     //Hay una relacion de pertenencia con User y Cv
     public function user()
     {
         return $this->belongsTo(User::class);
     }

}
