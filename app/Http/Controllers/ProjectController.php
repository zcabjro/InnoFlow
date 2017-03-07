<?php
/**
 * Created by PhpStorm.
 * Member: andreas
 * Date: 31/01/2017
 * Time: 20:40
 */

namespace App\Http\Controllers;


use App\Http\Requests\Project\ProjectEnrolmentRequest;
use App\Http\Requests\Project\RefreshProjectRequest;
use App\Models\VstsProject;
use App\Repositories\Module\ModuleRepoInterface;
use App\Services\Vsts\VstsApiService;
use App\Services\Common\Helper;
use App\Traits\JsonResponseTrait;
use App\Transformers\ProjectTransformer;
use GuzzleHttp\Exception\ClientException;


class ProjectController extends Controller
{
    use JsonResponseTrait;

    private $vstsService;
    private $moduleRepo;


    function __construct( VstsApiService $vstsService, ModuleRepoInterface $moduleRepo )
    {
        $this -> vstsService = $vstsService;
        $this -> moduleRepo = $moduleRepo;
    }


    public function index( RefreshProjectRequest $request )
    {
        $user = Helper::currentUser();

        try
        {
            $this -> vstsService -> updateUser( $user, $request -> refresh );
        }
        catch( ClientException $e )
        {
            $user -> resetVsts();
            return $this -> respondUnauthorized( 'Token cannot be refresh. Authorize with Vsts again.' );
        }

        $projects = [];

        foreach ( $user -> projects as $project )
        {
            $project -> is_owner = !is_null( $project -> account -> owner() );
            $projects[] = $project;
        }

        return fractal() -> collection( $projects, new ProjectTransformer );
    }


    public function show( VstsProject $vstsProject )
    {
        return fractal() -> parseIncludes( [ 'members' ] ) -> item( $vstsProject, new ProjectTransformer( true ) );
    }


    public function enrol( ProjectEnrolmentRequest $request, VstsProject $vstsProject, VstsApiService $vstsApiService )
    {
        $user = Helper::currentUser();

        $vstsApiService -> addWebHook( $user, $vstsProject );

        $vstsProject -> module_id = $request -> module_id;
        $vstsProject -> save();
    }
}