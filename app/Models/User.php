<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'mat',
        'rol_id',
        'edad',
        'sexo',
        'active',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    //Hay una relacion de uno a uno para los Cvs
    public function cv()
    {
        return $this->hasOne(Cv::class);
    }

    //Hay una relacion de uno a uno para los roles
    public function rol()
    {
        return $this->hasOne(Role::class);
    }




    //Hay una relacion de uno a muchas para las asesorias
    public function addAsesoria()
    {
        return $this->hasMany(Infoasesoria::class);
    }

    //Hay una relacion de uno a muchas para las registros a las asesorias
    public function addRegistro()
    {
        return $this->hasMany(Registro::class);
    }

    //Nos dice si ya respondio o no el formulario de credencial
    public function haRespondidoFormulario()
    {
        return $this->tramite !== null;
    }

}
