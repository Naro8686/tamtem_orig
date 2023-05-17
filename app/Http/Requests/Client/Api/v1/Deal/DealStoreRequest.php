<?php

namespace App\Http\Requests\Client\Api\v1\Deal;

use App\Rules\CategoriesRule;
use App\Rules\CategoriesSecondLevelRule;
use App\Rules\CitiesRule;
use App\Rules\QuestionsRule;
use Illuminate\Foundation\Http\FormRequest;
use \App\Models\Org\OrganizationDeal;

class DealStoreRequest extends FormRequest
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
		// тип сделки
		$type_deal = $this->get('type_deal');

		// макс. размер загружаемого файла по php.ini
		$max_size = (int)ini_get('upload_max_filesize') * 1000;

		// поля и на покупку и на продажу
		$rules = [
			'name' 								=> 'required|min:2|max:191', // название сделки
			'categories' 						=> ['required', 'array', new CategoriesSecondLevelRule()], // категории (надо проверить, чтобы была второго уровня!!!)
			'cities'							=> ['required', 'array', new CitiesRule()],
			'description' 						=> 'required|min:16|max:3000',  // описание сделки (заявки)
			'type_deal' 						=> 'required|in:' . OrganizationDeal::DEAL_TYPE_SELL . ','. OrganizationDeal::DEAL_TYPE_BUY,
			'tags'								=> 'sometimes|string|max:500', // список тэгов 
			//'subtype_deal' => 'required|in:' . OrganizationDeal::DEAL_SUBTYPE_USED . ','. OrganizationDeal::DEAL_SUBTYPE_NEW . ','. OrganizationDeal::DEAL_SUBTYPE_NA,
		];

		// продажа
		if($type_deal === OrganizationDeal::DEAL_TYPE_SELL){
			
			$rules = array_merge($rules, [
				'files'   => 'sometimes|array|max:3',
				'files.0' => 'sometimes|file|mimes:jpeg,bmp,png,doc,docx,xls,xlsx,pdf,rar,zip,7z|max:5000',
				'files.1' => 'sometimes|file|mimes:jpeg,bmp,png,doc,docx,xls,xlsx,pdf,rar,zip,7z|max:5000',
				'files.2' => 'sometimes|file|mimes:jpeg,bmp,png,doc,docx,xls,xlsx,pdf,rar,zip,7z|max:5000',
			]);

			$files = $this->input('files');

			if(is_array($files) and count($files) > 0){
				foreach($files  as $index => $val) {
					if ($val !== null)
						$rules['files.' . $index] = 'mimes:jpeg,bmp,png,doc,docx,xls,xlsx,pdf,rar,zip,7z|max:5000'; 
				   }
			}

		} // покупка
		elseif($type_deal === OrganizationDeal::DEAL_TYPE_BUY){

			$rules = array_merge($rules, [
				'unit_for_all'  					=> 'required|string|max:190', // единица измерения за полный объем товара (кг, литр)
				'budget_from' 						=> 'required|lte:budget_to|numeric|min:0', // бюджет от
				'budget_to' 						=> 'required|numeric|min:0',  // бюджет ДО
				'unit_for_unit'						=> 'required|string|max:190', // единица измерения за единицу товара (кг, литр)
				'budget_with_nds' 					=> 'required|boolean', // бюджет с НДС или без 
				'deadline_deal' 					=> 'required|date|date_format:"Y-m-d"|before:' . date('Y-m-d', strtotime('+33 day')) . '|after:' . date('Y-m-d'), 
				'date_of_event' 					=> 'sometimes|string|max:190', // дата проведения сделки, тут строка в свободной форме
				//'count_unit_in_volume' 	=> 'required|integer|min:1', // количество единиц в общем объеме (например объем 5 тонн - кол-во единиц 10
				// вопросы от покупателя
				'questions' 						=> 'required|array',
				'questions.dqh_doc_confirm_quality' => 'sometimes|string|max:2048', // Документы подтверждающие качество
				'questions.dqh_type_deal' 			=> 'sometimes|string|max:2048', // Тип сделки
				'questions.dqh_volume'  			=> 'required|string|max:2048', // объем общий
				'questions.dqh_logistics'  			=> 'required|string|max:2048', // Логистика
				'questions.dqh_payment_method'  	=> 'required|string|max:2048', // Способ оплаты
				'questions.dqh_payment_term'		=> 'required|string|max:2048', // Условия оплаты
				// 'questions.dqh_specification' 	=> 'required|string|max:2048', //Спецификация - сюда кинем инфу из 'description'  в самом коде уже
				'questions.dqh_min_party'  			=> 'required|string|max:2048', //  Минимальная партия

				//'is_need_kp' => 'sometimes|boolean',
				'file'   => 'sometimes|file|mimes:doc,docx,xls,xlsx,pdf,rar,zip,7z|max:10000', 
				'images' => 'sometimes|array',
			]);

			$images = $this->file('images');
			if(is_array($images) and count($images) > 0){
				// $images = count($images);
				foreach($images  as $index => $val) {
					if ($val !== null)
						$rules['images.' . $index] = 'mimes:jpeg,bmp,png|max:' . $max_size; 
				   }
			}
		}

		// if ($this->type_deal === OrganizationDeal::DEAL_TYPE_SELL){ 
	  	// 	$rules['budget_to'] = 'sometimes|numeric|min:0';
		// }
		// elseif($this->type_deal === OrganizationDeal::DEAL_TYPE_BUY){// Цены не обязательны только если куплю

		// 	//if($this->input('budget_from') !== null and $this->input('budget_to') !== null ){
		// 		$rules['budget_from'] 	= 'required|lte:budget_to|numeric|min:0';
		// 		$rules['budget_to'] 	= 'required|numeric|min:0';
		// 	//}
		// 	$rules['deadline_deal'] = 'sometimes|date|date_format:"Y-m-d"|before:' . date('Y-m-d', strtotime('+33 day')) . '|after:' . date('Y-m-d'); 
		// }

    	return $rules;
    }
}
