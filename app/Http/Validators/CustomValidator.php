<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 23/01/2017
 * Time: 16:44
 */


namespace App\Http\Validators;


use Illuminate\Validation\Validator;
use App\Models\User;

class CustomValidator extends Validator
{
    /**
     * Checks if $value is a latitude value
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateUser( $attribute, $value, $parameters, $validator )
    {
        return ( User::find( $value ) != null );
    }
}