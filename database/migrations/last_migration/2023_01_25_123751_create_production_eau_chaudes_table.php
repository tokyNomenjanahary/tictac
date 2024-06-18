<?php

use App\EtatDesLieu;
use App\Fonctionnement;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_eau_chaudes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('observation')->nullable();
            $table->unsignedBigInteger('etat_des_lieu_id')->nullable();
            $table->timestamps();
            $table->foreignIdFor(Fonctionnement::class)->nullable();
            $table->foreign('etat_des_lieu_id')->references('id')->on('etat_des_lieux')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_eau_chaudes');
    }
};
