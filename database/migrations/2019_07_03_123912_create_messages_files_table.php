<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages_files', function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer('message_id')->unsigned()->index()->comment('id сообщения');
            $table->integer('dialog_id')->unsigned()->index()->comment('id диалога');
            $table->integer('deal_id')->unsigned()->comment('Номер сделки');
            $table->integer('user_id')->unsigned()->index()->comment('Какой юзер загрузил файл (может админ)');
            $table->string('file_name', 255)->comment('Имя файла с раширением');
            $table->string('file_full_path', 255)->comment('Полный путь к файлу');
            $table->timestamps();

            $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages_files');
    }

}
