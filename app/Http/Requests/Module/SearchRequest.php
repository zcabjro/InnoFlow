<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 01/02/2017
 * Time: 23:41
 */


namespace App\Http\Requests\Module;

use App\Http\Requests\ApiRequest;


class SearchRequest extends ApiRequest
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

            'string' => 'required|string|min:2',

        ];
    }
}
