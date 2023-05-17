<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRowIsShippingIncludedToOrganizationsDealsMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations_deals_members', function (Blueprint $table) {
            $table->boolean('is_shipping_included')->after('price_offer')->default(false)->comment('включена ли доставка в стоимость');
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
            Schema::dropIfExists('is_shipping_included');
        });
    }
}
