<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRowsForResponseToOrganizationsDealsQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations_deals_questions', function (Blueprint $table) {
            $table->increments('id')->first();
            $table->string('question', 2048)->default('')->comment('вопрос для участников сделки');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { 
        Schema::table('organizations_deals_questions', function (Blueprint $table) {
            Schema::dropIfExists('id');
            Schema::dropIfExists('question');
        });
    }
}
