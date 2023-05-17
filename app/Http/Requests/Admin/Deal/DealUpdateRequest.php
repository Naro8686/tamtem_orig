<?php

namespace App\Http\Requests\Admin\Deal;

use App\Rules\CategoriesSecondLevelRule;
use App\Rules\CitiesRule;
use App\Rules\QuestionsRule;
use Illuminate\Foundation\Http\FormRequest;
use \App\Models\Org\OrganizationDeal;

class DealUpdateRequest extends FormRequest
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
			'name' 								=> 'required|min:2|max:191', // название сделки
			'categories' 						=> ['required', 'array', new CategoriesSecondLevelRule()], // категории (надо проверить, чтобы была второго уровня!!!)
			'cities'							=> ['required', 'array', new CitiesRule()],
			'type_deal' 						=> 'required|in:' . OrganizationDeal::DEAL_TYPE_SELL . ','. OrganizationDeal::DEAL_TYPE_BUY,
			'tags'								=> 'sometimes|string|max:500', // список тэгов через запятую

			// 'data.subtype_deal' => 'required|in:' . OrganizationDeal::DEAL_SUBTYPE_USED . ','. OrganizationDeal::DEAL_SUBTYPE_NEW . ','. OrganizationDeal::DEAL_SUBTYPE_NA,
			// 'data.is_need_kp' => 'sometimes|boolean',
			'images' => 'sometimes|array',
      	];
//dd($this->type_deal);
		if ($this->type_deal === OrganizationDeal::DEAL_TYPE_SELL){ 

			$rules = array_merge($rules, [
				'description' => 'required|min:16|max:3000',  // описание сделки (заявки)
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

		}
		elseif($this->type_deal === OrganizationDeal::DEAL_TYPE_BUY){// Цены не обязательны только если куплю
			
			$rules['description'] 	= 'required|min:16|max:65534';
			$rules['budget_from'] 	= 'required|lte:budget_to|numeric|min:0';
			$rules['budget_to'] 	= 'required|numeric|min:0';
			$rules['budget_all'] 	= 'required|numeric|min:0';
			$rules['budget_with_nds'] = 'required|boolean'; // бюджет с НДС или без 
			$rules['is_fake'] = 'required|boolean'; // фейковый заказ 
			//$rules['deadline_deal'] = 'sometimes|date|date_format:"Y-m-d"|before:' . date('Y-m-d', strtotime('+33 day')) . '|after:' . date('Y-m-d'); 
			$rules['deadline_deal'] = 'sometimes|date|date_format:"Y-m-d"|after:' . date('Y-m-d'); 
			$rules['file'] 			= 'sometimes|file|mimes:doc,docx,xls,xlsx,pdf,rar,zip,7z|max:10000'; 

			$rules['unit_for_unit'] = 'required|string|max:190'; // единица измерения за единицу товара (кг, литр)
			$rules['unit_for_all'] 	= 'required|string|max:190'; // единица измерения за полный объем товара (кг, литр)
			$rules['count_unit_in_volume'] 	= 'required|integer|min:1'; // количество единиц в общем объеме (например объем 5 тонн - кол-во единиц 1000 (в килограммах ,это указано в unit_for_unit))
			$rules['procent'] 		= 'required|integer|min:0'; // наш процент от сделки (смотрим по таблице зависимости процента от бюджета сделки
			$rules['val_for_all'] 	= 'required|integer|min:0'; // количество единиц товара (штук, ящиков...)
			$rules['commission'] 	= 'required|min:0|regex:/^\d+(\.\d{1,2})?$/'; // наша комиссия со сделки, в рублях
			$rules['date_of_event'] = 'sometimes|string|max:190'; // дата проведения сделки, тут строка в свободной форме


			// вопросы от покупателя
			$rules['questions']  = 'required|array';
			$rules['questions.dqh_volume']  		= 'required|string|max:2048'; // объем
			$rules['questions.dqh_specification']  	= 'required|string|max:2048'; //Спецификация
			$rules['questions.dqh_doc_confirm_quality']  	= 'required|string|max:2048'; // Документы подтверждающие качество
			$rules['questions.dqh_logistics']  	= 'required|string|max:2048'; // Логистика
			$rules['questions.dqh_type_deal']  	= 'required|string|max:2048'; // Тип сделки
			$rules['questions.dqh_payment_term']  	= 'required|string|max:2048'; // Условия оплаты
			$rules['questions.dqh_payment_method']  	= 'required|string|max:2048'; // Способ оплаты
			$rules['questions.dqh_min_party']  	= 'sometimes|string|max:2048'; //  Минимальная партия

		}

    	$max_size = (int)ini_get('upload_max_filesize') * 1000;
    	$images = $this->input('images');

    	if(is_array($images) and count($images) > 0){
        	foreach($images  as $index => $val) {
            	if ($val !== null)
            		$rules['images.' . $index] = 'mimes:jpeg,bmp,png|max:' . $max_size; 
           	}
    	}

    	return $rules;
    }
}
