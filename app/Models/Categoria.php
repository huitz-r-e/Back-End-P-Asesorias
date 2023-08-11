<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['nombre'];

    use HasFactory;

    public function asesoria(){
        return $this->hasMany(Infoasesoria::class, 'categoria_id');
    }

}

