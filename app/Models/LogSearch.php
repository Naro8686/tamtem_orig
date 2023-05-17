<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogSearch extends Model
{
    protected $table = 'log_searches';

    protected $fillable = [
        'user_id',
        'search_text',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
