<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColCountUnitVolumeToOrganizationsDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations_deals', function (Blueprint $table) {
            $table->integer('count_unit_in_volume')->after('unit_for_all')->default(1)->comment('количество единиц в общем объеме (например объем 5 тонн - кол-во единиц 1000 (в килограммах ,это указано в unit_for_unit))');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations_deals', function (Blueprint $table) {
            Schema::dropIfExists('count_unit_in_volume');
        });
    }
}
