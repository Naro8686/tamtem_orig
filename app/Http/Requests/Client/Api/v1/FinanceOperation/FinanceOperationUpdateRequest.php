<?php

namespace App\Http\Requests\Client\Api\v1\FinanceOperation;

use App\Models\FinanceOperation;
use App\Models\Payment\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class FinanceOperationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $slugs = Subscription::all()->implode('slug', ',');

      	$rules = [
            'status'    		=> 'required|in:' . FinanceOperation::PAYMENT_STATUS_ERROR . ','. FinanceOperation::PAYMENT_STATUS_WAITING . ','. FinanceOperation::PAYMENT_STATUS_PAID,
			'text'    			=> 'sometimes|string',
            'manager_id'    	=> 'sometimes|exists:users,id',
            'deal_id'           => 'sometimes|exists:organizations_deals,id', 
            'slug'              => 'sometimes|in:' . $slugs,
            'user_subscription_id'     => 'sometimes|exists:user_subscriptions,id',
      	];

    	return $rules;
    }
}
