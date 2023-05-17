<?php

namespace App\Http\Resources\DealsFile;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @property int id
 * @property int deal_id
 * @property int user_id
 * @property string file_name
 * @property string file_full_path
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class DealsFileResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'deal_id'           => $this->deal_id,
            'user_id'           => $this->user_id,
            'file_name'         => $this->file_name,
            'file_full_path'    => $this->file_full_path,
            'created_at'        => $this->created_at,  
            'updated_at'        => $this->updated_at,  
        ];
    }
}
