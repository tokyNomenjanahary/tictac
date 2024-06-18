<?php

use Illuminate\Support\Facades\DB;
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
        Schema::table('message_tickets', function (Blueprint $table) {
            $table->unsignedInteger('id_user_sender')->onDelete('cascade');
            $table->unsignedInteger('id_user_receiver')->onDelete('cascade');
            $table->foreign('id_user_sender')->references('id')->on('users');
            $table->foreign('id_user_receiver')->references('id')->on('users');
            $table->boolean('read')->default(false);
            $table->timestamp('date_sent')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message_tickets', function (Blueprint $table) {
            $table->dropForeign('id_user_sender');
            $table->dropForeign('id_user_receiver');
            $table->dropColumn('id_user_sender');
            $table->dropColumn('id_user_receiver');
            $table->dropColumn('read');
            $table->dropColumn('date_sent');
        });
    }
};
