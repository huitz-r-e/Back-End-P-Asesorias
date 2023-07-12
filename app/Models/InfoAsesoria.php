<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoAsesoria extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'desc','precio','active','user_id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
