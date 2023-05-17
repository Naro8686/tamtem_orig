<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountSetWinnersToOrganizationsStatisticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations_statistic', function (Blueprint $table) {
            $table->integer('count_set_winners')->after('count_responses')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations_statistic', function (Blueprint $table) {
            $table->dropIfExists('count_set_winners');
        });
    }
}
