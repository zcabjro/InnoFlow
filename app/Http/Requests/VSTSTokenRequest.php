<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 24/01/2017
 * Time: 12:52
 */


namespace App\Http\Requests;


class VSTSTokenRequest extends ApiRequest
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

            'access_token' => 'required',
            'token_type' => 'required',
            'expires_in' => 'required',
            'refresh_token' => 'required',

        ];
    }
}
