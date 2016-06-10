<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Noticia extends Model
{
    //establecemos nombre de la tabla
    protected $table = 'noticias';
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
        return $this->hasMany('Noticia_Comentario');
    }
    public function archivos() {
        return $this->belongsToMany('Archivo', 'noticia_archivos', 'noticia_id', 'archivo_id');
    }
}
