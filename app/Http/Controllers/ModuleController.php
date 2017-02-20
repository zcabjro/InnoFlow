<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 31/01/2017
 * Time: 20:43
 */

namespace App\Http\Controllers;

use App\Http\Requests\Module\NewModuleRequest;
use App\Models\Module;
use App\Repositories\Module\ModuleRepoInterface;
use App\Services\Common\Helper;
use App\Transformers\ModuleTransformer;


class ModuleController extends Controller
{
    private $moduleRepo;


    function __construct( ModuleRepoInterface $moduleRepo )
    {
        $this -> moduleRepo = $moduleRepo;
    }


    public function index()
    {
        $user = Helper::currentUser();

        $modules = $user -> modules;

        return fractal() -> collection( $modules, new ModuleTransformer );
    }


    public function show( Module $module )
    {
        return fractal() -> parseIncludes( [ 'admins', 'projects' ] ) -> item( $module, new ModuleTransformer );
    }


    public function store( NewModuleRequest $request )
    {
        $user = Helper::currentUser();

        $input = $request -> except( 'admins' );
        $input[ 'key' ] = bcrypt( $input[ 'key' ] );
        $input[ 'user_id' ] = $user -> user_id;
        $module = $this -> moduleRepo -> create( $input );

        if ( count( $admins = $request[ 'admins' ] ) )
        {
            $module -> admins() -> attach( $admins, [ 'is_owner' => false ] );
            $module -> admins() -> attach( $user, [ 'is_owner' => true ] );
        }
    }
}