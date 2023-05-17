<?php

namespace App\Http\Requests\Client\Api\v1\DealsFile;

use Illuminate\Foundation\Http\FormRequest;

class DealsFileStoreRequest extends FormRequest
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
      //  $max_size = (int)ini_get('upload_max_filesize') * 1000;
        $max_size = 10000; // 10Mb
        
      	$rules = [
            'user_id' 		=> 'required|integer|exists:users,id', 
            'deal_id' 		=> 'required|integer|exists:organizations_deals,id', 
            'file'        => 'required|file|mimes:doc,docx,xls,xlsx,pdf,rar,zip,7z|max:' . $max_size, 
      	];

    	return $rules;
    }
}