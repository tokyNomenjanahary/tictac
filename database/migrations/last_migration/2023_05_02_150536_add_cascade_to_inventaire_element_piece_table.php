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
        Schema::table('inventaire_element_piece', function (Blueprint $table) {
            $table->dropForeign(['inventaire_piece']);
            $table->foreign('inventaire_piece')->references('id')->on('inventaire_piece')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventaire_element_piece', function (Blueprint $table) {
            //
        });
    }
};
