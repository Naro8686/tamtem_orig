<?php

namespace App\Http\Requests\Client\Api\v1\FinanceOperation;

use App\Models\Payment\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class FinanceOperationPaytureStoreRequest extends FormRequest
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

  //  dd($this->all());//return var_dump();  
        $slugs = Subscription::all()->implode('slug', ',');
//dd( $slugs);
      	$rules = [
            'amount'            => ['required', 'integer', 'max:20000000'], // в копейках, ограничения payture
            'deal_id'           => 'required_with_all:slug|exists:organizations_deals,id', // в копейках, ограничения payture
            'slug'              => 'required_with_all:deal_id|in:' . $slugs, 
     	];

    	return $rules;
    }
}
