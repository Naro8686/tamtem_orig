<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealsProcentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals_procents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('budget_from')->default(0)->comment('цена сделки от');
            $table->integer('budget_to')->default(0)->comment('цена сделки до');
            $table->integer('procent')->default(0)->comment('наш процент');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deals_procents');
    }
}
