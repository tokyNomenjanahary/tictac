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
        Schema::create('logement_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logement_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            // $table->foreign('logement_id')->references('id')->on('logements')->onDelete('cascade');
            $table->string('file_name');
            $table->string('user_filename');
            $table->enum('media_type', ['0', '1']);
            $table->integer('ordre');
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
        Schema::dropIfExists('logement_files');
    }
};
