<?php

namespace App\Http\Requests\Client\Api\v1\DealsProcent;

use Illuminate\Foundation\Http\FormRequest;

class DealsProcentStoreRequest extends FormRequest
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

      	$rules = [
			'budget_from' 		=> 'required|lte:budget_to|gte:0|numeric',
			'budget_to' 		=> 'required|numeric', 
            'procent'           => 'required||gte:0|max:10000|integer', 
      	];

    	return $rules;
    }
}
