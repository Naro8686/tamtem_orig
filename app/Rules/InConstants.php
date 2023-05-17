<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class InConstants implements Rule
{
    /**
     * @var array
     */
    private $constants;

    /**
     * HasConstant constructor.
     * @param string $constantGroupName
     */
    public function __construct(string $constantGroupName)
    {
        $this->constants = config("constants.{$constantGroupName}", []);
    }

    /**
     * @param string $attribute
     * @param string $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array($value, Arr::flatten($this->constants));
    }

    /**
     * @return string
     */
    public function message()
    {
        return 'The selected :attribute is invalid.';
    }
}
