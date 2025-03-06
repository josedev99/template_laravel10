<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('usuario')->unique();
            $table->string('contr');
            $table->string('password');
            $table->string('departamento')->nullable();
            $table->string('telefono')->nullable();
            $table->string('dui')->nullable();
            $table->string('nit')->nullable();
            $table->string('cargo')->nullable();
            $table->string('estado');
            $table->string('categoria');
            $table->string('direccion')->nullable();
            $table->string('fecha_creacion');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('sucursal_id');
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
        Schema::dropIfExists('usuarios');
    }
}
