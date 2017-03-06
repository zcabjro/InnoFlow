<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 04/03/2017
 * Time: 14:48
 */

namespace App\Http\Requests\CodeReview;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Validator;


class NewCodeReviewRequest extends ApiRequest
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

            'title' => 'required|string|min:10',
            'description' => 'required|string|min:20',
            'commitIds' => 'required|commit_list',

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
            $commit_ids = array_unique( explode( ',', $this -> get( 'commitIds' ) ) );

            $all = $this -> all();
            $all[ 'commitIds' ] = $commit_ids;
            $this -> replace( $all );
        });

        return $validator;
    }
}
