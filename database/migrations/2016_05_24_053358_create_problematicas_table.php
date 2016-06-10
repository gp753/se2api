<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProblematicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problematicas', function (Blueprint $table) {
                       /*Campos comunes a todas las tablas*/
            
            $table->increments('id');
            $table->softDeletes();
            $table->timestamps();

        /*Campos propios de la clase*/

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('titulo');
            $table->string('contenido');
            $table->dateTime('fecha');
            //$table->boolean('activa');
            
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('problematicas');
    }
}
