<?php

namespace App\Models\Org;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Channel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    /**
    * Override parent boot and Call deleting event
    *
    * @return void
    */
    protected static function boot() 
    {
        parent::boot();
        
        // удалили тэг, значит прибьем его связи с организацией
        static::deleting(function($channel) {
            $channel->organizations()->detach();
        });
    }

    /**
     *  Get the organizations associated woth the given tag
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function organizations(){
        return $this->belongsToMany(\App\Models\Org\Organization::class, 'organization_channel')->withTimestamps();
    }
}
