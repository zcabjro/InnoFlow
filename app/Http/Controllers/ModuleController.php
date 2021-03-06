<?php
/**
 * Created by PhpStorm.
 * Member: andreas
 * Date: 31/01/2017
 * Time: 20:43
 */

namespace App\Http\Controllers;

use App\Http\Requests\Module\NewModuleRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Module\SearchRequest;
use App\Models\Module;
use App\Models\User;
use App\Repositories\Module\ModuleRepoInterface;
use App\Services\Common\Helper;
use App\Transformers\ModuleTransformer;
use App\Transformers\UserTransformer;
use Searchy;

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
        $input = $request -> except( 'admins', 'user_id' );
        $module = $this -> moduleRepo -> create( $input );

        $module -> admins() -> attach( $request -> user_id, [ 'is_owner' => true ] );
        if ( count( $admins = $request -> admins ) )
        {
            $module -> admins() -> attach( $admins, [ 'is_owner' => false ] );
        }

        return fractal() -> parseIncludes( [ 'admins', 'projects' ] ) -> item( $module, new ModuleTransformer );
    }


    public function search( SearchRequest $request )
    {
        $modules = Module::where( 'code', 'like', '%' . $request -> string . '%' ) -> orWhere( 'name', 'like', '%' . $request -> string . '%' ) -> get();
        return fractal() -> collection( $modules, new ModuleTransformer );

        /*
        $modules = Module::hydrate( Searchy::modules( [ 'code', 'name' ] ) -> query( $request -> string ) -> getQuery() -> limit( 10 ) -> get() -> toArray() );*/
    }


    public function searchAdmin( SearchRequest $request )
    {
        $users = User::where( 'email', 'like', '%' . $request -> string . '%' ) -> orWhere( 'username', 'like', '%' . $request -> string . '%' ) -> get();
        return fractal() -> collection( $users, new UserTransformer );

        /*
        $users = Member::hydrate( Searchy::users( [ 'email', 'username' ] ) -> query( $request -> string ) -> getQuery() -> limit( 10 ) -> get() -> toArray() );
        return fractal() -> collection( $users, new UserTransformer );*/
    }
}