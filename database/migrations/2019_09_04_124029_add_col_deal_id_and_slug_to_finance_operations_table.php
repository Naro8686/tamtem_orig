<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColDealIdAndSlugToFinanceOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('finance_operations', function (Blueprint $table) {
            $table->integer('deal_id')->unsigned()->after('text')->nullable()->comment('id сделки для оплаты');
            $table->string('slug')->after('deal_id')->default('')->comment('слаг типа оплаты');
            $table->integer('user_subscription_id')->unsigned()->after('slug')->nullable()->comment('id подписки из таблицы user_subscriptions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('finance_operations', function (Blueprint $table) {
            Schema::dropIfExists('deal_id');
            Schema::dropIfExists('slug');
            Schema::dropIfExists('user_subscription_id');
        });
    }
}
