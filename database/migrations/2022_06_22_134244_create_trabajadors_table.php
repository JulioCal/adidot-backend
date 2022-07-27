<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrabajadorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trabajadors', function (Blueprint $table) {
            $table->string('nombre');
            $table->integer('cedula')->primary();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['Administrador', 'Gerente' , 'Analista']);
            $table->enum('sexo', ['Masculino', 'Femanino', 'Otro']);
            $table->text('direccion');
            $table->string('estatus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trabajadors');
    }
}
