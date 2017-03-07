<?php
/**
 * Created by PhpStorm.
 * Member: andreas
 * Date: 01/03/2017
 * Time: 19:31
 */

namespace App\Http\Requests\Project;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Validator;


class RefreshProjectRequest extends ApiRequest
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

            'refresh' => 'boolean',

        ];
    }


    /**
     * Overrides parent class to check decode each field prior to validation.
     *
     * @return Validator
     */
    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        $validator -> after( function() use ( $validator )
        {
            $data = $this->all();
            $data[ 'refresh' ] = is_null( $this -> refresh ) ? false : $this -> refresh;
            $this -> replace( $data );
        });

        return $validator;
    }
}
