<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reunion extends Model
{
    use HasFactory;
    protected $fillable = ['expert_id', 'registro_id','urlmeet','confirmacion', 'tema','fecha'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
