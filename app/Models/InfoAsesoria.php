<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infoasesoria extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'desc','precio','active','user_id','categoria_id','imgcurso'];


    protected $appends = ['img_filename'];

    public function getImgFilenameAttribute()
    {
        return basename($this->imgcurso);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }

     public function reuniones()
    {
        return $this->hasMany(Reunion::class, 'infoa_id');
    }
}
