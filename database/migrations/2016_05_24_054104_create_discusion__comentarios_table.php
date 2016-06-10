<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscusionComentariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discusion__comentarios', function (Blueprint $table) {
            /*Campos Generales*/
            $table->increments('id');
            $table->softDeletes();
            $table->timestamps();


            /*Valores propios de la clase*/
            $table->integer('discusion_id')->unsigned();
            $table->foreign('discusion_id')->references('id')->on('discusiones');
            $table ->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('contenido');
           // $table->boolean('activo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('discusion__comentarios');
    }
}
