<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Org\OrganizationDeal;


class UpdateDefaultValTradingStatusColInOrganizationsDealsMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE organizations_deals_members MODIFY COLUMN trading_status ENUM ('" . 
            OrganizationDeal::DEAL_TRADING_STATUS_NA . "','" . 
            OrganizationDeal::DEAL_TRADING_STATUS_BANNED       . "','" . 
            OrganizationDeal::DEAL_TRADING_STATUS_MODERATION   . "','" . 
            OrganizationDeal::DEAL_TRADING_STATUS_WAITING_WINNER    . "','" . 
            OrganizationDeal::DEAL_TRADING_STATUS_WAITING_PAYMENT   . "','" . 
            OrganizationDeal::DEAL_TRADING_STATUS_FINISHED  . "') DEFAULT '" . OrganizationDeal::DEAL_TRADING_STATUS_NA . "';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE organizations_deals_members MODIFY COLUMN trading_status ENUM ('" . 
            OrganizationDeal::DEAL_TRADING_STATUS_NA . "','" . 
            OrganizationDeal::DEAL_TRADING_STATUS_BANNED       . "','" . 
            OrganizationDeal::DEAL_TRADING_STATUS_MODERATION   . "','" . 
            OrganizationDeal::DEAL_TRADING_STATUS_WAITING_WINNER    . "','" . 
            OrganizationDeal::DEAL_TRADING_STATUS_WAITING_PAYMENT   . "','" . 
            OrganizationDeal::DEAL_TRADING_STATUS_FINISHED  . "') DEFAULT '" . OrganizationDeal::DEAL_TRADING_STATUS_MODERATION . "';");
    }
}
