<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsReadedOwnerResponseAndIsReadedOwnerDealColsToOrganizationsDealsMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations_deals_members', function (Blueprint $table) {
            $table->boolean('is_readed_owner_response')->after('moderator_id')->default(true)->comment('отклик прочитан хозяином отклика');
            $table->boolean('is_readed_owner_deal')->after('is_readed_owner_response')->default(true)->comment('отклик прочитан хозяином заявки');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations_deals_members', function (Blueprint $table) {
            Schema::dropIfExists('is_readed_owner_response');
            Schema::dropIfExists('is_readed_owner_deal');
        });
    }
}
