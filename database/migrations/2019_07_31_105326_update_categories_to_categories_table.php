<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Category;

class UpdateCategoriesToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            Schema::table('categories', function (Blueprint $table) {
                DB::statement("SET foreign_key_checks=0");
                Category::truncate();
                Artisan::call('db:seed', array('--class' => 'CategorySeeder'));
                DB::statement("SET foreign_key_checks=1");
            });
        });

        DB::statement("SET foreign_key_checks=0");
            $maxId = Category::max('id');
            if($maxId and $maxId>0){
                DB::table('organizations_categories')->where('category_id', '>' , $maxId)->update(['category_id' => $maxId]);
                DB::table('organizations_deals_categories')->where('category_id', '>' , $maxId)->update(['category_id' => $maxId]);
            }
        DB::statement("SET foreign_key_checks=1");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            //
        });
    }
}
