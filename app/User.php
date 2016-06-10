<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    //establecemos nombre de la tabla
    protected $table = 'users';
    //protegemos los campos contra asignación masiva
    protected $guarded = ['*'];
    
    //activamos softdelete
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    //campos ocultos que no se devuelven nunca en un array
    protected $hidden = [
        'password', 
        'remember_token',
        'created_at',
        'updated_at'
    ];
    
    //relaciones
    Public function horariosPersonalizados() {
        return $this->hasMany('App\Horario');
    }

    
   
}
