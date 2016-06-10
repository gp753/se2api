<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Problematica extends Model
{
    //establecemos nombre de la tabla
    protected $table = 'problematicas';
    //protegemos los campos contra asignación masiva
    protected $guarded = ['*'];
    
    //activamos softdelete
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    //RELACIONES
     public function user() {
        return $this->belongsTo('User');
    }
    Public function comentarios() {
        return $this->hasMany('problematica');
    }
    public function archivos() {
        return $this->belongsToMany('Archivo', 'discusion_archivos', 'discusion_id', 'archivo_id');
    }


}
