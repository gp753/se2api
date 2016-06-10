<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartidoEquiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partido__equipos', function (Blueprint $table) {
          
            /*campos generales*/
            $table->increments('id');
            $table->softDeletes();
            $table->timestamps();
            
            /*campos propios de la clase*/
            $table->integer('equ_id')->unsigned();
            $table->foreign('equ_id')->references('id')->on('equipos');
            
            $table->integer('part_id')->unsigned();
            $table->foreign('part_id')->references('id')->on('partidos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('partido__equipos');
    }
}
