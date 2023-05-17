<?php

namespace App\Rules;

use App\Models\Category;
use Illuminate\Contracts\Validation\Rule;

class CategoriesSecondLevelRule implements Rule
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
        if (is_array($value)) {
            foreach ($value as $val) {
                if($this->categorySecondLevel($val) === false){
                    return false;   
                }     
            }
        }
        else{
            if($this->categorySecondLevel($value) === false){
                return false;   
            }   
        }

        return true;
    }

    public function categorySecondLevel($id)
    {
        $curCategoty = Category::find($id);

        if (!$curCategoty){
            return false;
        }

        $parentCategoty = $curCategoty->parent;
        if(!$parentCategoty or $parentCategoty->parent_id !== 0){ // категоря выше уровнем и чтобы выше нее не было, тогда текущая - второго уровня
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
        return 'Категоря должна существовать и быть второго уровня';
    }
}
