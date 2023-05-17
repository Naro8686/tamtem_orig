<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Org\OrganizationDeal;

class AddRowsForResponseToOrganizationsDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations_deals', function (Blueprint $table) {
            $table->string('unit_for_unit')->default('')->comment('единица измерения за единицу товара (кг, литр)');
            $table->string('unit_for_all')->default('')->comment('единица измерения за полный объем товара (кг, литр)');
            $table->integer('procent')->default(0)->comment('наш процент от сделки (смотрим по таблице зависимости процента от бюджета сделки)');
            $table->integer('val_for_all')->default(1)->comment('количество единиц товара (штук, ящиков...)');
            $table->double('commission', 10, 2)->default(0)->comment('наша комиссия со сделки, в рублях');
            $table->string('date_of_event')->default('')->comment('дата проведения сделки, тут строка в свободной форме');
            $table->enum('trading_status', [OrganizationDeal::DEAL_TRADING_STATUS_NA,
                                            OrganizationDeal::DEAL_TRADING_STATUS_WAITING_WINNER,  
                                            OrganizationDeal::DEAL_TRADING_STATUS_WAITING_PAYMENT,   
                                            OrganizationDeal::DEAL_TRADING_STATUS_FINISHED]
                                            )->default(OrganizationDeal::DEAL_TRADING_STATUS_NA)->index()->comment('статус торгов по сделке ');
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
            Schema::dropIfExists('unit_for_unit');
            Schema::dropIfExists('unit_for_all');
            Schema::dropIfExists('procent');
            Schema::dropIfExists('val_for_all');
            Schema::dropIfExists('commission');
            Schema::dropIfExists('date_of_event');
            Schema::dropIfExists('trading_status');
        });
    }
}
