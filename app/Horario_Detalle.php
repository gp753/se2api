<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Horario_Detalle extends Model
{
    //establecemos nombre de la tabla
    protected $table = 'horario__detalles';
    //protegemos los campos contra asignación masiva
    protected $guarded = ['*'];
    
    //activamos softdelete
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    //RELACIONES
     public function horario() {
        return $this->belongsTo('App\Horario');
    }
}
