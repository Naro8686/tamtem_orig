<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddColCreatedAtToOrganizationsDealsQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations_deals_questions', function (Blueprint $table) {
            $table->timestamps();
            Schema::disableForeignKeyConstraints();
            $table->dropForeign('organizations_deals_questions_question_id_foreign');
            Schema::enableForeignKeyConstraints();
            $table->foreign('question_id')->references('id')->on('deals_questions_headers')->onDelete('cascade');
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
            Schema::disableForeignKeyConstraints();
            Schema::dropForeign('organizations_deals_questions_headers_question_id_foreign');
            Schema::enableForeignKeyConstraints();
            $table->foreign('question_id')->references('id')->on('deals_questions')->onDelete('cascade');
        });
    }
}
