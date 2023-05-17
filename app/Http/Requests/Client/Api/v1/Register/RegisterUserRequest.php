<?php

namespace App\Http\Requests\Client\Api\v1\Register;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CategoriesRule;
use App\Rules\WorkTimeRule;
use App\Rules\InnExistRule;

class RegisterUserRequest extends FormRequest
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

        return [
            'name' => 'required|min:1|max:190',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|max:32',
            'phone'    => 'required|regex:/(^(\d+)$)/u|min:10|max:10',
			'license_agreement' => 'accepted',
            'message_possible' => 'required_with:false',
            'categories'        => ['required', 'array', new CategoriesRule()],    
            'notice_allowed'     => 'required|boolean',

            // регистрировать ли юзера автоматом
            'to_register_user'        => 'required|boolean',    

            // параллельно создаем организацию
            'agent_id'                       => 'required_with:agent_inn|numeric',
            'agent_inn'                      => 'required_with:agent_id|min:10|max:12',
            

            'organization.first_name'        => 'sometimes|required|max:64',
            'organization.second_name'       => 'sometimes|required|max:64',
            'organization.middle_name'       => 'sometimes|required|max:64',
            'organization.phone'             => 'sometimes|required|regex:/(^(\d+)$)/u|min:10|max:10',
            'organization.org_city_id'       => 'sometimes|required|exists:cities,id',
            'organization.org_name'          => 'required|max:255',
            // 'organization.org_inn'           => 'required|unique:organizations,org_inn|min:10|max:12',
            'organization.org_inn'           => ['required', 'min:10', 'max:12', new InnExistRule()],
            'organization.org_kpp'           => 'sometimes|required|min:9|max:9',
            'organization.org_address'       => 'sometimes|required|max:191',
            'organization.org_address_legal' => 'sometimes|required|max:191',
            'organization.org_legal_form_id' => 'sometimes|required|exists:organizations_legal_forms,id',
            'organization.org_director'      => 'sometimes|required|max:64',
            'organization.org_site'          => 'sometimes|required|url|max:64',
            'organization.org_products'      => 'sometimes|required|max:5000', // описание товаров и услуг
            'organization.org_description'   => 'sometimes|required|max:5000', // краткое описание организации
            'organization.org_work_time'     => ['sometimes', 'array', new WorkTimeRule()],
            'organization.logo'              => 'sometimes|required|mimes:jpeg,bmp,png|max:' . $max_size,
            'organization.image_1'           => 'sometimes|required|mimes:jpeg,bmp,png|max:' . $max_size,
            'organization.image_2'           => 'sometimes|required|mimes:jpeg,bmp,png|max:' . $max_size,
            'organization.image_3'           => 'sometimes|required|mimes:jpeg,bmp,png|max:' . $max_size,
            'organization.video'             => 'sometimes|required|required|mimes:mp4|max:25600',     
           // 'organization.categories'        => ['sometimes', 'array', new CategoriesRule()],

            'organization.org_manager_post'  => 'sometimes|required|min:1|max:190',
            'organization.org_okved'         => 'required|min:1|max:190',
            'organization.org_ogrn'          => 'required|min:1|max:190',
            'organization.org_is_active'     => 'sometimes|required|boolean',
            'organization.org_registration_date' => 'sometimes|required|date_format:"Y-m-d',

        ];
    }
}
