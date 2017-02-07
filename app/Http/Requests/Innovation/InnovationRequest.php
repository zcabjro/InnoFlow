<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 26/01/2017
 * Time: 23:53
 */

namespace App\Http\Requests\IdeExtension;

use App\Http\Requests\ApiRequest;
use App\Repositories\User\UserRepoInterface;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Validation\Validator;


class InnovationRequest extends ApiRequest
{
    use JsonResponseTrait;


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = App::call( [ $this, 'findUser' ] );
        if ( is_null( $user ) )
        {
            return false;
        }

        $this -> merge( [ 'user' => $user ] );
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
            'code' => 'required|base64_encoded'

        ];
    }


    /**
     * Overrides parent class to check decode each field prior to validation.
     *
     * @return Validator
     */
    protected function getValidatorInstance()
    {
        $data = $this -> all();

        if( isset( $data[ 'email' ] ) )
        {
            $data[ 'email' ] = base64_decode( $data[ 'email' ] );
        }

        if( isset( $data[ 'password' ] ) )
        {
            $data[ 'password' ] = base64_decode( $data[ 'password' ] );
        }

        $this -> replace( $data );

        $validator = parent::getValidatorInstance();

        $validator -> after( function() use ( $validator )
        {
            if( isset( $data[ 'password' ] ) )
            {
                $data = $this->all();
                $data['code'] = base64_decode($data['code']);
                $this->replace($data);
            }
        });

        return $validator;
    }


    /**
     * Returns a permission denied response in case authorize() returns false.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forbiddenResponse()
    {
        return $this -> respondForbidden( 'Permission denied. Invalid user credentials.' );
    }


    /**
     * Finds the user that matches the credentials (i.e. email and password).
     *
     * @param UserRepoInterface $userRepo
     * @return bool
     */
    public function findUser( UserRepoInterface $userRepo )
    {
        return $userRepo -> findByCredentials( $this[ 'email' ], $this[ 'password' ] );
    }
}
