<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Votacion_Opcion extends Model
{
    //        //establecemos nombre de la tabla
    protected $table = 'votacion__opciones';
    //protegemos los campos contra asignación masiva
    protected $guarded = ['*'];
    
    //activamos softdelete
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    //RELACIONES
     public function votaciones() {
        return $this->belongsTo('Votaciones');
    }
    Public function votacion_opciones() {
        return $this->hasMany('Votacion_Voto');
    }
}
