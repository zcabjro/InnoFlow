<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 04/03/2017
 * Time: 14:48
 */

namespace App\Http\Requests\CodeReview;

use App\Http\Requests\ApiRequest;
use App\Models\Commit;
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

            'commitIds' => 'required|string',

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
            $commit_ids = explode( ',', $this -> get( 'commitIds' ) );
            dd( $commit_ids );
            $filtered = [];

            foreach ( $commit_ids as $commit_id )
            {
                if ( !is_null( Commit::find( $commit_id ) ) )
                {
                    $filtered[] = $commit_id;
                }
            }

            $all = $this -> all();
            $all[ 'commitIds' ] = $filtered;
            $this -> replace( $all );
        });

        return $validator;
    }
}
