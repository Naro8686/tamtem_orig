<?php

namespace App\Http\Resources\FinanceOperation;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @property int id
 * @property int user_id
 * @property int payment_id
 * @property int amount
 * @property int payment_system
 * @property int status
 * @property string $text
 * @property int manager_id
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class FinanceOperationResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'user_id'           => $this->user_id,
            'payment_id'        => $this->payment_id,
            'amount'            => $this->amount,
            'payment_system'    => $this->payment_system,
            'status'            => $this->status,
            'text'              => $this->text, 
            'manager_id'        => $this->manager_id,  
            'created_at'        => $this->created_at,  
            'updated_at'        => $this->updated_at,  
        ];
    }
}
