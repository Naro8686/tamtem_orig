<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResponsesFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responses_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('response_id')->unsigned()->index()->comment('Номер отклика');
            $table->integer('user_id')->comment('Какой юзер загрузил файл (может админ)');
            $table->string('file_name', 255)->comment('Имя файла с раширением');
            $table->string('file_full_path', 255)->comment('Полный путь к файлу');
            $table->timestamps();
            $table->foreign('response_id')->references('id')->on('organizations_deals_members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('responses_files');
    }
}
