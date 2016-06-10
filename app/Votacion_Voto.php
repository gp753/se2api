<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Votacion_Voto extends Model
{
    //VOTACION VOTO Y USUARIO


            //establecemos nombre de la tabla
    protected $table = 'votacion__votos';
    //protegemos los campos contra asignación masiva
    protected $guarded = ['*'];
    
    //activamos softdelete
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    //RELACIONES
     public function user() {
        return $this->belongsTo('User');
    }

      public function Votacion_Opcion() {
        return $this->belongsTo('Votacion_Opcion');
    }

    public function Votacion() {
        return $this->belongsTo('Votacion');
    }
   
}
