<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votaciones', function (Blueprint $table) {
                       //Datos generales
            $table->increments('id');
            $table->softDeletes();
            $table->timestamps();

            /*Valores propios de la clase*/

            $table->integer('user_id')->unsigned();//para saber quien creo la votación solo puede ser un administrador
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('titulo');
            $table->text('contenido');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
           // $table->date('fecha_inicio_efectiva')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('votaciones');
    }
}


