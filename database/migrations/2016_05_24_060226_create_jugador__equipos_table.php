<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJugadorEquiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jugador__equipos', function (Blueprint $table) {
            
            /*campos generales*/
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            
            /*campos propios de la clase*/
            $table->integer('jug_id')->unsigned();
            $table->foreign('jug_id')->references('id')->on('jugadors');
            $table->integer('equ_id')->unsigned();
            $table->foreign('equ_id')->references('id')->on('equipos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('jugador__equipos');
    }
}
