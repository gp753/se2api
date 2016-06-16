<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTorneosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('torneos', function (Blueprint $table) {
            /*Campos comunes a todas las tablas*/
            $table->increments('id');
            $table->softDeletes();
            $table->timestamps();

            /*Campos propios de la clase*/
            $table->integer('dep_id')->unsigned()->nullable();
            $table->foreign('dep_id')->references('id')->on('deportes');
            $table->string('nombre');
            $table->integer('campeon_id')->unsigned()->nullable();
            $table->text('descripcion')->nullable();
            $table->integer('archivo_id')->unsigned()->nullable();
            $table->foreign('archivo_id')->references('id')->on('archivos');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('torneos');
    }
}
