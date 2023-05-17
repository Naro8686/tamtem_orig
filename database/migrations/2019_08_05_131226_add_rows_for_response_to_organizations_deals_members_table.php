<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Org\OrganizationDeal;

class AddRowsForResponseToOrganizationsDealsMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations_deals_members', function (Blueprint $table) {
            $table->increments('id')->first();
            $table->enum('trading_status', [OrganizationDeal::DEAL_TRADING_STATUS_NA,
                                OrganizationDeal::DEAL_TRADING_STATUS_WAITING_WINNER,  
                                OrganizationDeal::DEAL_TRADING_STATUS_WAITING_PAYMENT,   
                                OrganizationDeal::DEAL_TRADING_STATUS_FINISHED]
                                )->default(OrganizationDeal::DEAL_TRADING_STATUS_NA)->index()->comment('статус торгов по сделке');
            $table->integer('price_offer')->default(0)->comment('ценовое предложение откликающегося');
            $table->string('notice', 1024)->default('')->comment('примечание к отклику по сделке');
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
            Schema::dropIfExists('id');
            Schema::dropIfExists('trading_status');
            Schema::dropIfExists('price_offer');
            Schema::dropIfExists('notice');
        });
    }
}
