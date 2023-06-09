<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealsFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('deal_id')->unsigned()->index()->comment('Номер сделки');
            $table->integer('user_id')->comment('Какой юзер загрузил файл (может админ)');
            $table->string('file_name', 255)->comment('Имя файла с раширением');
            $table->string('file_full_path', 255)->comment('Полный путь к файлу');
            $table->timestamps();
            $table->foreign('deal_id')->references('id')->on('organizations_deals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deals_files');
    }
}
