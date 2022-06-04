<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MatchWith implements Rule
{
    protected $match_value;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct( $match_value )
    {
        $this->match_value = $match_value;
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
        return ($value == $this->match_value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute dosen\'t match';
    }
}
