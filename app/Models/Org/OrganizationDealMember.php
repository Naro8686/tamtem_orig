<?php

namespace App\Models\Org;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class OrganizationDealMember extends Model
{

    use Notifiable;

    protected $table = 'organizations_deals_members';
    protected $fillable = [
        'deal_id',
        'organization_id',
        'trading_status',
        'price_offer', 
        'is_shipping_included',
        'notice',
        'is_readed_owner_response',
        'is_readed_owner_deal',
    ];

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
    public function deal()
    {
        return $this->belongsTo(\App\Models\Org\OrganizationDeal::class, 'deal_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organizationWithUser()
    {
        //return $this->hasManyThrough(\App\Models\User::class ,\App\Models\Org\Organization::class,  'owner_id', 'id', 'organization_id'  );
        return $this->organization()->with('owner');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        // $org = $this->organization()->owner()
        return $this->organization->owner;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ownerDeal()
    {
        // $org = $this->organization()->owner()
        return $this->deal()->first()->user();
    }

     /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(\App\Models\Org\OrganizationDealAnswer::class, 'member_id' );
    }

    public function answersByDeals()
    {
        return $this->hasMany(\App\Models\Org\OrganizationDealAnswer::class, 'deal_id', 'deal_id');
    }

    public function answersByOrganizations()
    {
        return $this->hasMany(\App\Models\Org\OrganizationDealAnswer::class, 'organization_id', 'organization_id');
    }

    public function files()
    {
        return $this->hasMany(\App\Models\ResponsesFile::class, 'response_id');
    }
}
