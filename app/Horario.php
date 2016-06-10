<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Horario extends Model
{
    //establecemos nombre de la tabla
    protected $table = 'horarios';
    //protegemos los campos contra asignación masiva
    protected $guarded = ['*'];
    
    //activamos softdelete
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    //RELACIONES
     public function users() {
        return $this->belongsTo('User');
    }
    Public function detalle() {
        return $this->hasMany('App\Horario_Detalle');
    }
}
