<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColChequeSentToFinanceOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('finance_operations', function (Blueprint $table) {
            $table->boolean('cheque_sent')->after('status')->default(false)->comment('отправлен ли чек');
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
            Schema::dropIfExists('cheque_sent');
        });
    }
}
