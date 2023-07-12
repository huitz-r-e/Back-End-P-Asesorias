<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infostatu extends Model
{
    use HasFactory;
    protected $fillable = ['estado', 'descripcion'];

}
