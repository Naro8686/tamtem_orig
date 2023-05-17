<?php

namespace App\Http\Requests\Client\Api\v1\Deal;

use Illuminate\Foundation\Http\FormRequest;

class DealTakeRequest extends FormRequest
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

   // dd($this->all());
      	$rules = [
			  // вопросы от покупателя
			'questions'  => 'required|array',
			'questions.dqh_volume'  		=> 'required|string|max:2048',// объем
			'questions.dqh_specification'  	=> 'required|string|max:2048', //Спецификация
			'questions.dqh_doc_confirm_quality'  	=> 'required|string|max:2048', // Документы подтверждающие качество
			'questions.dqh_logistics'  	=> 'required|string|max:2048', // Логистика
			'questions.dqh_type_deal'  	=> 'required|string|max:2048', // Тип сделки
			'questions.dqh_payment_term'  	=> 'required|string|max:2048', // Условия оплаты
			'questions.dqh_payment_method'  	=> 'required|string|max:2048', // Способ оплаты
			'questions.dqh_min_party'  	=> 'sometimes|string|max:2048', //  Минимальная партия

			'price_offer' => 'required|min:0|max:999999999|regex:/^\d+(\.\d{1,2})?$/', //'required|integer|min:0|max:9999999999',
			'is_shipping_included' => 'sometimes|boolean',
			'notice' => 'sometimes|max:1023',
			'file'   => 'sometimes|file|mimes:doc,docx,xls,xlsx,pdf,rar,zip,7z|max:10000',
			'images' => 'sometimes|array|max:3',
			'images.*' => 'mimes:jpeg,jpg,bmp,png|max:10000',

        ];

    	return $rules;
    }
}
