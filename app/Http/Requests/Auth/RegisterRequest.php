<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Validator;


class RegisterRequest extends ApiRequest
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

            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:10',
            'username' => 'required|min:5|max:255|unique:users'

        ];
    }

    /**
     * Overrides parent class to check if admins exists and are valid users.
     *
     * @return Validator
     */
    public function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        $validator -> after( function() use ( $validator )
        {
            $all = $this -> all();
            $all[ 'password' ] = bcrypt( $this -> password );
            $this -> replace( $all );
        });

        return $validator;
    }
}
