<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('objet');
            $table->string('description');
            $table->date('start_time');
            $table->date('finish_time');
            $table->string('adresse_rdv');
            $table->integer('status');
            $table->unsignedInteger('userId_proprietaire');
            $table->unsignedInteger('userId_locataire');
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
        Schema::dropIfExists('agendas');
    }
};
