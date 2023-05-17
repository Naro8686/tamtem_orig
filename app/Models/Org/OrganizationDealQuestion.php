<?php

namespace App\Models\Org;

use Illuminate\Database\Eloquent\Model;

class OrganizationDealQuestion extends Model
{
    protected $table = 'organizations_deals_questions';
    protected $fillable = ['deal_id', 'question_id', 'question'];

    public function questionHeader()
    {
      return $this->belongsTo(\App\Models\DealQuestionHeader::class, 'question_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deal()
    {
        return $this->belongsTo(\App\Models\Org\OrganizationDeal::class, 'deal_id');
    }

    
}
