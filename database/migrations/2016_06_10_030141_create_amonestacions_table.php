<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmonestacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amonestacions', function (Blueprint $table) {
            $table->increments('id');
            $table->softDeletes();
            $table->timestamps();
            
            /*campos propios de la clase*/
            $table->integer('par_id')->unsigned();
            $table->foreign('par_id')->references('id')->on('partidos');
            $table->integer('jug_id')->unsigned();
            $table->foreign('jug_id')->references('id')->on('jugadors');
            $table->integer('equ_id')->unsigned();
            $table->foreign('equ_id')->references('id')->on('equipos');
            $table->text('amonestacion');
            $table->text('descripcion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('amonestacions');
    }
}
