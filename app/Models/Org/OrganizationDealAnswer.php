<?php

namespace App\Models\Org;

use Illuminate\Database\Eloquent\Model;

class OrganizationDealAnswer extends Model
{
    protected $table = 'organizations_deals_answers';
    protected $fillable = ['member_id', 'deal_id','organization_id', 'question_id', 'is_agree', 'answer'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(\App\Models\Org\Organization::class, 'organization_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(\App\Models\Org\OrganizationDealQuestion::class, 'question_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deal()
    {
        return $this->belongsTo(\App\Models\Org\OrganizationDeal::class, 'deal_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(\App\Models\Org\OrganizationDealMember::class, 'member_id');
    }
}
