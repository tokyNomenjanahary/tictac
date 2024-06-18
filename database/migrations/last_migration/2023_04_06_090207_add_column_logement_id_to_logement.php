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
        Schema::table('logements', function (Blueprint $table) {
            $table->unsignedBigInteger('logement_id');
            $table->foreign('logement_id')
                ->references('id')->on('logements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logements', function (Blueprint $table) {
            $table->dropForeign('logement_id'); 
            $table->dropIndex('logement_id');
            $table->dropColumn('logement_id');
        });
    }
};
