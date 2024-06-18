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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string("Subject");
            $table->string("Priority");
            $table->string("Status");
            $table->date("Date_dernier_modif");
            $table->date("Date_resolution");
            $table->unsignedBigInteger('type_ticket_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('user_created_ticket');
            $table->unsignedBigInteger('user_destinated_ticket');
            $table->foreign('type_ticket_id')->references('id')->on('type_tickets');
            $table->foreign('location_id')->references('id')->on('locations_proprietaire');
            // $table->foreign('user_created_ticket')->references('id')->on('users');
            // $table->foreign('user_destinated_ticket')->references('id')->on('users');
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
        Schema::dropIfExists('tickets');
    }
};
