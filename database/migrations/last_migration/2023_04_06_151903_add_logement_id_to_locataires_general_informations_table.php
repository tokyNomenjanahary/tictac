<?php

use App\Logement;
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
        Schema::table('locataires_general_informations', function (Blueprint $table) {
            $table->foreignIdFor(Logement::class);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locataires_general_informations', function (Blueprint $table) {
            $table->dropColumn('logement_id');
        });
    }
};
