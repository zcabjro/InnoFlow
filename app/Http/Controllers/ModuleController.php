<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 31/01/2017
 * Time: 20:43
 */

namespace App\Http\Controllers;

use App\Http\Requests\Module\NewModuleRequest;
use App\Repositories\Module\ModuleRepoInterface;


class ModuleController extends Controller
{
    private $moduleRepo;


    public function __construct( ModuleRepoInterface $moduleRepo )
    {
        $this -> moduleRepo = $moduleRepo;
    }


    public function index()
    {
        $modules = [];
        $modules[] = ['id' => 1, 'code' => 'COMPGS02', 'name' => 'Software Abstractions and Systems Integration'];
        $modules[] = ['id' => 2, 'code' => 'COMP3013', 'name' => 'Database and Information Management Systems'];
        $modules[] = ['id' => 3, 'code' => 'COMP3080', 'name' => 'Computer Graphics'];
        $modules[] = ['id' => 4, 'code' => 'COMP3035', 'name' => 'Networked Systems'];

        return $modules;
    }


    public function store( NewModuleRequest $request )
    {
        $module = $this -> moduleRepo -> create( $request -> except( 'admins' ) );

        if ( count( $admins = $request[ 'admins' ] ) )
        {
            $module -> users() -> attach( $admins );
        }
    }
}