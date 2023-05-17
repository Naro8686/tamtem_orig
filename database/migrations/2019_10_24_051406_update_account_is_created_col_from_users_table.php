<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\User;

class UpdateAccountIsCreatedColFromUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN account_is_created VARCHAR (191) NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN account_is_created ENUM ('" . 
            User::ACCOUNT_CREATED_USER . "','" . 
            User::ACCOUNT_CREATED_AUTO . "','" . 
            User::ACCOUNT_CREATED_FORM . "') DEFAULT '" . User::ACCOUNT_CREATED_USER . "';");
    }
}
