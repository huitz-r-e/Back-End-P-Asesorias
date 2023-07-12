<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    use HasFactory;
    protected $fillable = ['rutaCv', 'comentariocv','statuscv_id','user_id'];


    //Hay una relacion de pertenencia con User y Cv
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
