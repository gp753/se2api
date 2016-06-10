<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Actividad extends Model
{
    //establecemos nombre de la tabla
    protected $table = 'actividades';
    //protegemos los campos contra asignación masiva
    protected $guarded = ['*'];

    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    //RELACIONES
     public function user() {
        return $this->belongsTo('User');
    }
    
    Public function movimientos() {
        return $this->hasMany('Actividad');
    }
    public function archivos() {
        return $this->belongsToMany('Archivo', 'actividad_archivos', 'actividad_id', 'archivo_id');
    }
    
    
}

    