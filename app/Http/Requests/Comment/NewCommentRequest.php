<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 04/03/2017
 * Time: 17:00
 */


namespace App\Http\Requests\Comment;

use App\Http\Requests\ApiRequest;
use App\Services\Common\Helper;


class NewCommentRequest extends ApiRequest
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

            'message' => 'required|string|min:20',

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
            $all[ 'user_id' ] = Helper::currentUser() -> user_id;
            $all[ 'code_review_id' ] = $this -> route() -> parameter( 'codeReview' ) -> code_review_id;
            $this -> replace( $all );
        });

        return $validator;
    }
}
