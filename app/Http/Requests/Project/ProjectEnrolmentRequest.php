<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 19/02/2017
 * Time: 20:29
 */

namespace App\Http\Requests\Project;

use App\Http\Requests\ApiRequest;
use App\Models\Module;
use App\Repositories\Module\ModuleRepoInterface;
use App\Services\Common\Helper;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\App;


class ProjectEnrolmentRequest extends ApiRequest
{
    use JsonResponseTrait;

    const ALREADY_ENROLED = 'A project can only be enrolled once.';
    const WRONG_CREDENTIALS = 'Module credentials are incorrect.';
    const NOT_OWNER = 'Only the owner of the project can enrol into a class.';

    private $errorMessage;


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Helper::currentUser();
        $vstsProject = $this -> route() -> parameter( 'vstsProject' );

        if ( !is_null( $vstsProject -> module_id ) )
        {
            $this -> errorMessage = self::ALREADY_ENROLED;
            return false;
        }

        if ( is_null( $module = App::call( [ $this, 'findModule' ] ) ) )
        {
            $this -> errorMessage = self::WRONG_CREDENTIALS;
            return false;
        }

        if ( $vstsProject -> account -> owner() -> user_id != $user -> user_id )
        {
            $this -> errorMessage = self::NOT_OWNER;
            return false;
        }

        $this -> request -> add( [ 'module_id' => $module -> module_id ] );

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

            'code' => 'required',
            'key' => 'required',

        ];
    }

    /**
     * Returns a permission denied response in case authorize() returns false.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forbiddenResponse()
    {
        switch ( $this -> errorMessage )
        {
            case self::ALREADY_ENROLED:
                return $this -> respondBadRequest( $this -> errorMessage );

            case self::WRONG_CREDENTIALS: case self::NOT_OWNER:
                return $this -> respondUnauthorized( $this -> errorMessage );

            default:
                return null;

        }
    }

    /**
     * Finds the module that matches the credentials.
     *
     * @param ModuleRepoInterface $moduleRepo
     * @return Module
     */
    public function findModule( ModuleRepoInterface $moduleRepo )
    {
        return $moduleRepo -> findByCredentials( $this -> code, $this -> key );
    }
}
