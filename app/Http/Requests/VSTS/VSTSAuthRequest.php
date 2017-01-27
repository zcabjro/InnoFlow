<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 23/01/2017
 * Time: 16:37
 */

namespace App\Http\Requests\VSTS;

use App\Http\Requests\ApiRequest;


class VSTSAuthRequest extends ApiRequest
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

            'code' => 'required|string',
            'state' => 'required|user',

        ];
    }
}
