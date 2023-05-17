<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Org\OrganizationDealMember;
use App\Models\Org\OrganizationDealAnswer;

class AddRowMemberIdToOrganizationsDealsAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations_deals_answers', function (Blueprint $table) {
            $table->integer('member_id')->unsigned()->after('id')->nullable()->comment('id из таблицы organizations_deals_members');
            
        });

        $members = OrganizationDealMember::all();

        // сделано сохранением по одной, ибо БД сейчас мало наполнена
        if($members){
          foreach($members as $member){
                \DB::statement("
                    UPDATE `organizations_deals_answers` SET `member_id`  = '" . $member->id. "'
                    WHERE `organizations_deals_answers`.`organization_id` = '" . $member->organization_id. "'
                    AND `organizations_deals_answers`.`deal_id` = '" . $member->deal_id. "'
                ");       
          }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations_deals_answers', function (Blueprint $table) {
            Schema::dropIfExists('member_id');
        });
    }
}
