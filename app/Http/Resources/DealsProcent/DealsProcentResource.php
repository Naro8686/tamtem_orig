<?php

namespace App\Http\Resources\DealsProcent;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @property int id
 * @property int budget_from
 * @property int budget_to
 * @property int procent
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class DealsProcentResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'budget_from'       => $this->budget_from,
            'budget_to'         => $this->budget_to,
            'procent'           => $this->procent,
            'created_at'        => $this->created_at,  
            'updated_at'        => $this->updated_at,  
        ];
    }
}
