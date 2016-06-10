<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Actividad_archivo extends Model
{
    //establecemos nombre de la tabla
    protected $table = 'actividad_archivos';
    //protegemos los campos contra asignación masiva
    protected $guarded = ['*'];
    
    //activamos softdelete
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    //RELACIONES
     public function User() {
        return $this->belongsTo('User');
    }
     public function Noticia() {
        return $this->belongsTo('Actividad');
    }
}
