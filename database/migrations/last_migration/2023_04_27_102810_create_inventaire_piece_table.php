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
        Schema::create('inventaire_piece', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventaire_id');
            $table->string('identifiant');
            $table->foreign('inventaire_id')->references('id')->on('inventaires');
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
        Schema::dropIfExists('inventaire_piece');
    }
};
