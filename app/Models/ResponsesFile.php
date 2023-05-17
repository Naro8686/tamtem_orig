<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResponsesFile extends Model
{
       
    protected $table = 'responses_files';

    protected $fillable = [
        'response_id',
        'user_id',
        'file_name',
        'file_full_path',
    ];
    static protected $sortable = ['id', 'response_id', 'user_id', 'created_at'];
  
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
    public function response()
    {
        return $this->belongsTo(\App\Models\Org\OrganizationDealMember::class, 'response_id'); // обратное отношение , местный внешний ключ  response_id
    }
}
