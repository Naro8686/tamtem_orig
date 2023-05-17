<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Org\OrganizationDeal;
use Carbon\Carbon;

class AddColModeratorIdAndPlannedFinishToOrganizationsDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations_deals', function (Blueprint $table) {
            $table->timestamp('planned_finish')->after('finished_at')->nullable()->comment('планируемая дата завершения заявки');
            $table->integer('moderator_id')->unsigned()->after('manager_id')->nullable()->comment('кто последний модерировал сделку');
        });

        $deals = OrganizationDeal::all();

        // сделано сохранением по одной, ибо БД сейчас мало наполнена
        if($deals){
          foreach($deals as $deal){
                if($deal->deadline_deal != null and (Carbon::parse($deal->deadline_deal) < Carbon::now()->addDays(30))){
                    $deal->planned_finish = Carbon::parse($deal->deadline_deal); 
                }
                elseif($deal->published_at != null ){
                    $deal->planned_finish = Carbon::parse($deal->published_at)->addDays(30); 
                }
                else{
                    $deal->planned_finish = Carbon::parse($deal->created_at)->addDays(30); 
                }
                $deal->save();
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
        Schema::table('organizations_deals', function (Blueprint $table) {
            Schema::dropIfExists('planned_finish');
            Schema::dropIfExists('moderator_id');
        });
    }
}
