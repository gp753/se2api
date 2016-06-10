<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movimiento extends Model
{
    //establecemos nombre de la tabla
    protected $table = 'movimientos';
    //protegemos los campos contra asignación masiva
    protected $guarded = ['*'];

    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    //RELACIONES
     public function user() {
        return $this->belongsTo('User');
    }
    
    Public function actividades() {
        return $this->belongsTo('Movimiento');
    }
    public function archivos() {
        return $this->belongsToMany('Archivos', 'movimeinto_archivos', 'movimiento_id', 'archivo_id');
    }
}
