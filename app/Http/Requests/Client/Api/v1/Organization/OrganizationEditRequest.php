<?php

namespace App\Http\Requests\Client\Api\v1\Organization;

use App\Rules\WorkTimeRule;
use App\Rules\CategoriesRule;
use Illuminate\Foundation\Http\FormRequest;

class OrganizationEditRequest extends FormRequest
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
        $max_size = (int)ini_get('upload_max_filesize') * 1000;

        $rules = [
           // 'logo' => 'sometimes|required|mimes:png|dimensions:max_width=200,max_height=200|max:1024',
            'first_name'        => 'sometimes|required|max:64',
            'second_name'       => 'sometimes|required|max:64',
            'middle_name'       => 'sometimes|required|max:64',
            'phone'             => 'sometimes|required|regex:/(^(\d+)$)/u|min:10|max:10',
            'org_city_id'       => 'sometimes|required|exists:cities,id',
            'org_name'          => 'sometimes|required|max:255',
            'org_inn'           => 'sometimes|required|min:10|max:12',
            'org_kpp'           => 'sometimes|required|min:9|max:9',
            'org_address'       => 'sometimes|required|max:191',
            'org_address_legal' => 'sometimes|required|max:191',
            'org_legal_form_id' => 'sometimes|required|exists:organizations_legal_forms,id',
            'org_director'      => 'sometimes|required|max:64',
            'org_site'          => 'sometimes|required|url|max:64',
            'org_products'      => 'sometimes|required|max:5000', // описание товаров и услуг
            'org_description'   => 'sometimes|required|max:5000', // краткое описание организации
            'org_work_time'     => ['sometimes', 'array', new WorkTimeRule()],
            'logo'              => 'sometimes|required|mimes:jpeg,bmp,png|max:' . $max_size,
            'image_1'           => 'sometimes|required|mimes:jpeg,bmp,png|max:' . $max_size,
            'image_2'           => 'sometimes|required|mimes:jpeg,bmp,png|max:' . $max_size,
            'image_3'           => 'sometimes|required|mimes:jpeg,bmp,png|max:' . $max_size,
            'video'             => 'sometimes|required|required|mimes:mp4|max:25600',     
            'categories'        => ['sometimes', 'array', new CategoriesRule()],

            'org_manager_post'  => 'sometimes|required|min:1|max:190',
            'org_okved'         => 'sometimes|required|min:1|max:190',
            'org_ogrn'          => 'sometimes|required|min:1|max:190',
            'org_is_active'     => 'sometimes|required|boolean',
            'org_registration_date' => 'sometimes|required|date_format:"Y-m-d',

        ];

        return $rules;
    }

    
}
