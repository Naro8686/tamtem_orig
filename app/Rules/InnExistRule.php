<?php

namespace App\Rules;

use App\Classes\Business\WorkTime;
use Illuminate\Contracts\Validation\Rule;
use App\Models\Org\Organization;

class InnExistRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (Organization::select('id')->where('org_inn','=', $value)->first()){
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Данная компания уже зарегистрирована';
    }
}
