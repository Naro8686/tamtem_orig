<?php

namespace App\Http\Requests\Admin\Client;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\CategoriesRule;
use App\Rules\InnExistRule;

class ClientStoreUserRequest extends FormRequest
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
            'user.email' => 'required|email|unique:users,email',
            'user.name'  => 'required|min:2|max:32', //сделал минимум 2 символа (в каком то из Request минимум 5 то есть человек и именем Ян пролетает) :).
            'user.phone' => 'required|regex:/(^(\d+)$)/u|min:10|max:10',


            'organization.org_inn'           => ['required', 'min:10', 'max:12', new InnExistRule()],
            'organization.org_kpp'           => 'sometimes|required|min:9|max:9',
            'organization.org_name'          => 'required|max:255',
            'organization.categories'        => ['required', 'array', new CategoriesRule()],    
            'organization.org_legal_form_id' => 'sometimes|required|exists:organizations_legal_forms,id',
            'organization.org_director'      => 'sometimes|required|max:64',
            'organization.org_address_legal' => 'sometimes|required|max:191',
            'organization.phone'             => 'sometimes|required|regex:/(^(\d+)$)/u|min:10|max:10',
            'organization.org_description'   => 'sometimes|required|max:5000', // краткое описание организации
            'organization.org_registration_date' => 'sometimes|date_format:"Y-m-d',
            
        ];
    }
}
