<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jugador extends Model
{
    //establecemos nombre de la tabla
    protected $table = 'jugadors';
    //protegemos los campos contra asignación masiva
    protected $guarded = ['*'];
    
    //activamos softdelete
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    //RELACIONES
    public function user() {
        return $this->belongsTo('User');
    }

    Public function partido_tantos() {
        return $this->hasMany('Jugador');
    }
    public function equipos() {
        return $this->belongsToMany('Equipo', 'jugador__equipos', 'jug_id', 'equ_id');
    }
}
