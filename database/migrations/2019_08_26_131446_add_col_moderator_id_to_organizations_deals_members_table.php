<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColModeratorIdToOrganizationsDealsMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations_deals_members', function (Blueprint $table) {
            $table->integer('moderator_id')->unsigned()->after('notice')->nullable()->comment('кто последний модерировал отклик');
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
            Schema::dropIfExists('moderator_id');
        });
    }
}
