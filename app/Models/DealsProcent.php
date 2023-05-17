<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DealsProcent extends Model
{
    use SoftDeletes;
   
    protected $table = 'deals_procents';

    // protected $casts = [
    //     'budget_from' => 'double',
    //     'budget_to'  => 'double',
    //     'procent'  => 'double',
    // ];

    protected $fillable = [
        'budget_from',
        'budget_to',
        'procent',
    ];

}
