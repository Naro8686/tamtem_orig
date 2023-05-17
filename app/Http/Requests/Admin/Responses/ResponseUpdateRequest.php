<?php

namespace App\Http\Requests\Admin\Responses;

use Illuminate\Foundation\Http\FormRequest;

class ResponseUpdateRequest extends FormRequest
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
        return [
            'id'   => 'required|integer|min:1|exists:cities,id',
            'price_offer' => 'sometimes|min:0|max:999999999|regex:/^\d+(\.\d{1,2})?$/', //'sometimes|min:1|integer',
            'notice' => 'sometimes|max:1024',
            'is_shipping_included' => 'sometimes|boolean',
            
            // вопросы от покупателя
			'answers'  => 'sometimes|array',
			'answers.dqh_volume'  		        => 'sometimes|boolean',// объем
			'answers.dqh_specification'  	        => 'sometimes|boolean', //Спецификация
			'answers.dqh_doc_confirm_quality'  	=> 'sometimes|boolean', // Документы подтверждающие качество
			'answers.dqh_logistics'  	            => 'sometimes|boolean', // Логистика
			'answers.dqh_type_deal'  	            => 'sometimes|boolean', // Тип сделки
			'answers.dqh_payment_term'  	        => 'sometimes|boolean', // Условия оплаты
			'answers.dqh_payment_method'  	    => 'sometimes|boolean', // Способ оплаты
			'answers.dqh_min_party'  	            => 'sometimes|boolean', //  Минимальная партия

        ];
    }
}
