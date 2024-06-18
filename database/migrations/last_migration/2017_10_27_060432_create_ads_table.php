<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ad_title');
            $table->text('ad_description');
            $table->string('address');
            $table->string('lat', 50);
            $table->string('lng', 50);
            $table->float('min_rent', 15,4);
            $table->float('max_rent', 15,4)->nullable();
            $table->date('available_date');
            $table->enum('ad_type', ['0', '1', '2', '3', '4']);
            $table->enum('ad_status', ['0', '1']);
            $table->enum('admin_approve', ['0', '1']);
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
        Schema::dropIfExists('ads');
    }
}
