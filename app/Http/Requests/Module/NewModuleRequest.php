<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 01/02/2017
 * Time: 14:06
 */

namespace App\Http\Requests\Module;

use App\Http\Requests\ApiRequest;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use App\Services\Common\Helper;


class NewModuleRequest extends ApiRequest
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

            'name' => 'required|string|min:5|max:100',
            'description' => 'required|string',
            'code' => 'required|string|unique:modules,code|min:5|max:100',
            'key' => 'required|string|min:10|max:100',
            'admins' => 'string|int_list'

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
            $user = Helper::currentUser();
            $admin_ids = explode( ',', $adminList = $this -> get( 'admins' ) );
            $admin_ids = array_diff( $admin_ids, Array( $user -> user_id ) );
            $admin_ids = array_unique( $admin_ids );
            $filtered = [];

            foreach ( $admin_ids as $admin_id )
            {
                if ( !is_null( User::find( $admin_id ) ) )
                {
                    $filtered[] = $admin_id;
                }
            }

            $all = $this -> all();
            $all[ 'admins' ] = $filtered;
            $all[ 'key' ] = bcrypt( $this -> key );
            $all[ 'user_id' ] = $user -> user_id;

            $this -> replace( $all );
        });

        return $validator;
    }
}
