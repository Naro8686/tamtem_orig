<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCategoryNameToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("
            UPDATE `categories` SET name = 'Товары и оборудование для производства, складов, услуг' WHERE name = 'Товары и оборудование для производства, складов и предоставления услуг'
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("
            UPDATE `categories` SET name = 'Товары и оборудование для производства, складов и предоставления услуг' WHERE name = 'Товары и оборудование для производства, складов, услуг'
        ");
    }
}
