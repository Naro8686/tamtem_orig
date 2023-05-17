<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRowsForResponseToOrganizationsDealsAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations_deals_answers', function (Blueprint $table) {
            $table->boolean('is_agree')->after('question_id')->default(false)->comment('согласен ли с условиями');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations_deals_answers', function (Blueprint $table) {
            Schema::dropIfExists('is_agree');
        });
    }
}
