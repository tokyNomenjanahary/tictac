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
        Schema::create('packages_stockage_documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('info');
            $table->string('amount');
            $table->string('duration')->nullable();
            $table->string('unite')->nullable();
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
        Schema::dropIfExists('packages_stockage_documents');
    }
};
