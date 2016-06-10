<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Votacion extends Model
{
        //establecemos nombre de la tabla
    protected $table = 'votaciones';
    //protegemos los campos contra asignación masiva
    protected $guarded = ['*'];
    
    //activamos softdelete
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    //RELACIONES
     public function user() {
        return $this->belongsTo('User');
    }
    Public function opciones() {
        return $this->hasMany('Votacion_Opcion');
    }

    Public function voto() {
        return $this->hasMany('Votacion_Voto');
    }
   /* public function archivos() {
        return $this->belongsToMany('Archivo', 'discusion_archivos', 'discusion_id', 'archivo_id');
    }*/
}
