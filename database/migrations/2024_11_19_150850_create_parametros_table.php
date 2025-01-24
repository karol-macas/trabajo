<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParametrosTable extends Migration
{
    public function up()
    {
        Schema::create('parametros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');

             //relacion de parametro con departamento

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parametros');
    }
}
