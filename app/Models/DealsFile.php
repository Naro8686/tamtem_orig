<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealsFile extends Model
{
       
    protected $table = 'deals_files';

    protected $fillable = [
        'deal_id',
        'user_id',
        'file_name',
        'file_full_path',
    ];
    static protected $sortable = ['id', 'deal_id', 'user_id', 'created_at'];
  
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deal()
    {
        return $this->belongsTo(\App\Models\Org\OrganizationDeal::class, 'deal_id'); // обратное отношение , местный внешний ключ  deal_id
    }
}
