<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProblematicaComentariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problematica__comentarios', function (Blueprint $table) {
              /*Campos Generales*/
            $table->increments('id');
            $table->softDeletes();
            $table->timestamps();


            /*Valores propios de la clase*/
            $table->integer('problematica_id')->unsigned();
            $table->foreign('problematica_id')->references('id')->on('problematicas');
            $table ->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('contenido');
            //$table->boolean('activo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('problematica__comentarios');
    }
}
