<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotacionVotosTable extends Migration
{
    /**
     * VOTACION VOTOS Y USUARIOS.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votacion__votos', function (Blueprint $table) {
            
            //Datos generales
            $table->increments('id');
            $table->softDeletes();
            $table->timestamps();


         /*Valores propios de la clase*/

            $table->integer('votacion_opcion_id')->unsigned();
            $table->foreign('votacion_opcion_id')->references('id')->on('votacion__opciones');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('votacion_id')->unsigned();
            $table->foreign('votacion_id')->references('id')->on('votaciones');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('votacion__votos');
    }
}
