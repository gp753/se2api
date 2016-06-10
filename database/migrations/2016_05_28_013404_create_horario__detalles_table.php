<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorarioDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horario__detalles', function (Blueprint $table) {
            /*Campos comunes a todas las tablas*/
            $table->increments('id');
            $table->softDeletes();
            $table->timestamps();

            /*Campos propios de la clase*/
            $table->integer('horario_id')->unsigned();
            $table->foreign('horario_id')->references('id')->on('horarios');
            $table->string('codigo_materia');
            $table->string('nombre_materia');
            $table->string('semestre');
            $table->string('inicio');
            $table->string('fin');
            $table->string('dia');
            $table->string('profesor');
            $table->string('aula');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('horario__detalles');
    }
}
