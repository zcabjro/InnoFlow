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
use League\Flysystem\Exception;

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


    /**
     * Checks if the $value is a list of numbers e.g. "1,2,3,4"
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateIntList( $attribute, $value, $parameters, $validator )
    {
        $idRegEx = '[1-9]+[0-9]*';
        if( preg_match( '/^' . $idRegEx . '(,' . $idRegEx . ')*$/', $value ) )
        {
            return true;
        }

        return false;
    }


    /**
     * Checks if the $value is base_64 encoded.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateBase64Encoded( $attribute, $value, $parameters, $validator )
    {
        return base64_encode( base64_decode( $value, true ) ) === $value;
    }
}