<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Amonestacion extends Model
{
    //establecemos nombre de la tabla
    protected $table = 'amonestacions';
    //protegemos los campos contra asignación masiva
    protected $guarded = ['*'];
    
    //activamos softdelete
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    //RELACIONES
     public function partidos() {
        return $this->belongsTo('Partido');
    }
    public function jugadors() {
        return $this->belongsTo('Jugador');
    }
    public function equipos() {
        return $this->belongsTo('Equipo');
    }

}
