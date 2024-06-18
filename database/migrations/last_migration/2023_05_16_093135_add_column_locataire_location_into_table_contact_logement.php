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
        Schema::table('contact_logements', function (Blueprint $table) {
            $table->unsignedBigInteger('garants_location')->nullable();
            $table->unsignedBigInteger('garants_locataire')->nullable();
            $table->foreign('garants_location')->references('id')->on('garants')->onDelete('cascade');
            $table->foreign('garants_locataire')->references('id')->on('locataires_garants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventaires', function (Blueprint $table) {
            $table->dropForeign('garants_location');
            $table->dropForeign('garants_locataire');
            $table->dropColumn('garants_location');
            $table->dropColumn('garants_locataire');
        });
    }
};
