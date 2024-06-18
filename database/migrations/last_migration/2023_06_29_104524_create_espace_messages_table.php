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
        Schema::create('espace_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user_sender');
            $table->unsignedBigInteger('id_user_receiver');
            $table->unsignedBigInteger('id_ticket')->nullable();
            $table->string('sujet')->nullable();
            $table->string('message');
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
        Schema::dropIfExists('espace_messages');
    }
};
