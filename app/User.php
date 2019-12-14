<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;//plugins para roles y permisos
use Illuminate\Support\Facades\Hash;//encriptar
use Illuminate\Database\Eloquent\SoftDeletes;//eliminado logico algoritmo que trae el framework

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;//plugins para roles y permisos

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //necesario para la columna de eliminado logico
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'tipo_identificacion',
        'identificacion',
        'email',
        'tipo_telefono',
        'telefono',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    //hash paswword
    public function setPasswordAttribute($password)
    {
      $this->attributes['password'] = Hash::make($password);//encripta $5622 == $5622
      /*
      public function setPasswordAttribute(hola)
      si hola = bd.pw no lo hace asi
      si Hash::make(hola) = bd.d($82823hdn8djbaj)
      */
    }

}
