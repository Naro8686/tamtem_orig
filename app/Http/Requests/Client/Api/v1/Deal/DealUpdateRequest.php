<?php

namespace App\Http\Requests\Client\Api\v1\Deal;

use App\Rules\CategoriesRule;
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
		//	'id' =>  'required|integer|exists:organizations_deals,id',
			'type_deal' => 'required|in:' . OrganizationDeal::DEAL_TYPE_SELL . ','. OrganizationDeal::DEAL_TYPE_BUY,
			'subtype_deal' => 'required|in:' . OrganizationDeal::DEAL_SUBTYPE_USED . ','. OrganizationDeal::DEAL_SUBTYPE_NEW . ','. OrganizationDeal::DEAL_SUBTYPE_NA,
			'name' => 'sometimes|min:1|max:190',
			'is_need_kp' => 'sometimes|boolean',
			'categories' => ['required', 'array', new CategoriesRule()],
			'cities' => ['required', 'array', new CitiesRule()],
			'images' => 'sometimes|array',
			'description' => 'sometimes|min:16|max:65534',
			//'deadline_deal' => 'sometimes|null|date|date_format:"Y-m-d"|before:' . date('Y-m-d', strtotime('+30 day')) . '|after:' . date('Y-m-d'), 
			'date_of_event' => 'sometimes|string|max:190',
			'unit_for_unit' => 'sometimes|string|max:190',
			'file'   => 'sometimes|file|mimes:doc,docx,xls,xlsx,pdf,rar,zip,7z|max:10000', 
      	];

		if ($this->type_deal === OrganizationDeal::DEAL_TYPE_SELL){ 
			$rules['budget_to'] = 'sometimes|numeric|min:0';
		}
		elseif($this->type_deal === OrganizationDeal::DEAL_TYPE_BUY){// Цены не обязательны только если куплю
			
			$rules['budget_from'] 	= 'required|lte:budget_to|numeric|min:0';
			$rules['budget_to'] 	= 'required|numeric|min:0';
			$rules['deadline_deal'] = 'sometimes|date|date_format:"Y-m-d"|before:' . date('Y-m-d', strtotime('+33 day')); 
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
