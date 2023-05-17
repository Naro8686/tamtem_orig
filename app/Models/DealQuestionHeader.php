<?php

namespace App\Models;

use App\Traits\DataTable;
use Illuminate\Database\Eloquent\Model;

class DealQuestionHeader extends Model
{
    use DataTable;

    protected $table = 'deals_questions_headers';
    protected $fillable = ['name', 'slug'];
    static protected $sortable = ['id', 'name', 'slug'];
    // public $timestamps = false;

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    //  */
    // public function dealsQuestions()
    // {
    //     return $this->belongsToMany(\App\Models\Org\OrganizationDeal::class, 'organizations_deals_questions', 'deal_id', 'question_id');
    // }
}
