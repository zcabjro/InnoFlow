<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 26/01/2017
 * Time: 23:48
 */

namespace App\Http\Controllers;


use App\Http\Requests\Innovation\InnovationRequest;
use App\Repositories\Innovation\InnovationRepoInterface;
use App\Services\Common\Helper;
use App\Transformers\InnovationTransformer;


class InnovationController extends Controller
{
    private $innovationRepo;


    public function __construct( InnovationRepoInterface $innovationRepo )
    {
        $this -> innovationRepo = $innovationRepo;
    }


    public function index()
    {
        $user = Helper::currentUser();
        return fractal() -> collection( $user -> innovations, new InnovationTransformer );
    }


    public function store( InnovationRequest $request )
    {
        $this -> innovationRepo -> create( $request -> all() );
    }
}