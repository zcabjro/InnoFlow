<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 26/01/2017
 * Time: 23:53
 */

namespace App\Http\Requests\Innovation;

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
        if ( is_null( $this -> email ) || is_null( $this -> password ) || is_null( $this -> code ) )
        {
            return true;
        }

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

            'email' => 'required|string',
            'password' => 'required|string',
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
        $validator = parent::getValidatorInstance();

        $validator -> after( function() use ( $validator )
        {
            $data = $this->all();

            if( isset( $data[ 'code' ] ) )
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
        return $this -> respondUnauthorized( 'Permission denied. Invalid user credentials.' );
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
