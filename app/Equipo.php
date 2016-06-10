<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Equipo extends Model
{
   //establecemos nombre de la tabla
    protected $table = 'equipos';
    //protegemos los campos contra asignación masiva
    protected $guarded = ['*'];
    
    //activamos softdelete
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    //RELACIONES
     public function deportes() {
        return $this->belongsTo('Deporte');
    }
    public function partido_tantos(){
        return $this->hasMany('Equipo');
    }
    
    public function partidos() {
        return $this->belongsToMany('Partido', 'partido__equipos', 'equ_id', 'par_id');
    }
    public function jugadors() {
        return $this->belongsToMany('Jugador', 'jugador__equipos', 'equ_id', 'jug_id');
    }
}
