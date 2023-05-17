<?php

namespace App\Http\Requests\Admin\DealsQuestionsHeaders;

use Illuminate\Foundation\Http\FormRequest;

class DealsQuestionsHeadersStoreRequest extends FormRequest
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
            'name' => 'required|min:1|max:255|unique:deals_questions_headers,name',
            'slug' => 'required|min:1|max:255|unique:deals_questions_headers,slug',
        ];
    }
}
