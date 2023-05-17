<?php

namespace App\Models\Org;

use Illuminate\Database\Eloquent\Model;

class OrganizationStatistic extends Model
{
  
    protected $table = 'organizations_statistic';

    protected $fillable = [
        'organization_id',
        'agent_id',
        'inn',
        'count_buy_bids',
        'count_responses',
        'count_set_winners',
        'response_ballance',
    ];


    //protected $dates = [];
    static protected $sortable = ['id', 'inn'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(\App\Models\Org\Organization::class);
    }

  
    // /**
    //  * @return collection
    //  */
    // public function countResponsesAdminka()
    // {
    //     $members = $this->members;
        
    //     $count_response_active_after_moderate = 0;
    //     $count_response_moderate = 0;

    //     foreach ($members as $member) {
    //         if($member->pivot->trading_status === self::DEAL_TRADING_STATUS_MODERATION){
    //             $count_response_moderate++;
    //         }
    //         if($member->pivot->trading_status === self::DEAL_TRADING_STATUS_WAITING_PAYMENT or $member->pivot->trading_status === self::DEAL_TRADING_STATUS_WAITING_WINNER){
    //             $count_response_active_after_moderate++;
    //         }
    //     }

    //     $countResponses = collect([
    //         'count_response' => $members->count(),
    //         'count_response_moderate' => $count_response_moderate,
    //         'count_response_active_after_moderate' => $count_response_active_after_moderate
    //     ]);

    //     return $countResponses;
    // }

    // /**
    //  * countResponses - кол-во откликов на сайте
    //  *
    //  * @return collection
    //  */
    // public function countResponses()
    // {
    //     $members = $this->membersFromOrganizationMembersTable;
  
    //     $count_response_active_after_moderate = 0;
    //     $count_response_moderate = 0;

    //     foreach ($members as $member) {
    //         if($member['trading_status'] === self::DEAL_TRADING_STATUS_MODERATION){
    //             $count_response_moderate++;
    //         }
    //         if($member['trading_status'] === self::DEAL_TRADING_STATUS_WAITING_PAYMENT or $member['trading_status'] === self::DEAL_TRADING_STATUS_WAITING_WINNER){
    //             $count_response_active_after_moderate++;
    //         }
    //     }

    //     $countResponses = collect([
    //         'count_response' => $members->count(),
    //         'count_response_moderate' => $count_response_moderate,
    //         'count_response_active_after_moderate' => $count_response_active_after_moderate
    //     ]);

    //     return $countResponses;
    // }


}
