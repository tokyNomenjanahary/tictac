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
        Schema::create('quittance', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bien');
            $table->integer('user_id_destinataire');
            $table->integer('user_id_sender');
            $table->integer('location_id');
            $table->integer('montant');
            $table->integer('finance_id');
            $table->string('description');
            $table->string('quittance');
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
        Schema::dropIfExists('quittances');
    }
};
