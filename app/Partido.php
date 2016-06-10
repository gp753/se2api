<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Partido extends Model
{
    //establecemos nombre de la tabla
    protected $table = 'partidos';
    //protegemos los campos contra asignación masiva
    protected $guarded = ['*'];
    
    //activamos softdelete
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    //RELACIONES
   
    public function torneos() {
        return $this->belongsTo('Torneo');
    }
    public function deportes() {
        return $this->belongsTo('Deporte');
    }
    
    Public function partido__tantos() {
        return $this->hasMany('Partido');
    }
    public function equipos() {
        return $this->belongsToMany('Equipo', 'partido__equipos', 'par_id', 'equ_id');
    }
}
