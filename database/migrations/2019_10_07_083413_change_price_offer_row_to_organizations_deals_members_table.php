<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePriceOfferRowToOrganizationsDealsMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE organizations_deals_members MODIFY COLUMN price_offer DOUBLE(10,2) NOT NULL DEFAULT '0.00';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE organizations_deals_members MODIFY COLUMN price_offer INT NOT NULL DEFAULT '0';");
    }
}
