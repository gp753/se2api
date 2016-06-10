<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Deporte extends Model
{
    //establecemos nombre de la tabla
    protected $table = 'deportes';
    //protegemos los campos contra asignación masiva
    protected $guarded = ['*'];
    
    //activamos softdelete
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    //RELACIONES
    
    Public function equipos() {
        return $this->hasMany('Deporte');
    }
    Public function partidos() {
        return $this->hasMany('Deporte');
    }
    Public function torneos() {
        return $this->hasMany('Deporte');
    }
}
