<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 26/01/2017
 * Time: 23:53
 */

namespace App\Http\Requests\IdeExtension;

use App\Http\Requests\ApiRequest;


class InnovationRequest extends ApiRequest
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

            'email' => 'required',
            'password' => 'required',
            'code' => 'required|string'

        ];
    }
}
