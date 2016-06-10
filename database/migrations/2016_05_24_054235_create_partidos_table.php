<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partidos', function (Blueprint $table) {
            
            /*campos generales*/
            $table->increments('id');
            $table->softDeletes();
            $table->timestamps();
            
            /*campos propios de la clase*/
            $table->integer('dep_id')->unsigned();
            $table->foreign('dep_id')->references('id')->on('deportes');
            $table->integer('tor_id')->unsigned();
            $table->foreign('tor_id')->references('id')->on('torneos');
            $table->date('fecha');
            $table->string('lugar');
            $table->integer('puntaje_ganador')->nullable();
            $table->integer('puntaje_derrotado')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('partidos');
    }
}
