<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotacionOpcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votacion__opciones', function (Blueprint $table) {
         //Datos generales
            $table->increments('id');
            $table->softDeletes();
            $table->timestamps();


         /*Valores propios de la clase*/
            $table->integer('votacion_id')->unsigned(); //para cierta votación creada cual es la respuesta del usuario, presupone solo una respuesta a varias preguntas
            $table->foreign('votacion_id')->references('id')->on('votaciones');
            $table->text('descripcion');
            $table->text('valor');
           

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('votacion__opciones');
    }
}



