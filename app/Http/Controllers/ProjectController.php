<?php
/**
 * Created by PhpStorm.
 * User: andreas
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
use App\Transformers\VstsProjectTransformer;
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
            $refresh = is_null( $request -> refresh ) ? false : $request -> refresh;
            $this -> vstsService -> updateUser( $user, $refresh );
        }
        catch( ClientException $e )
        {
            $user -> resetVsts();
            return $this -> respondUnauthorized( 'Token cannot be refresh. Authorize with Vsts again.' );
        }

        $projects = [];

        foreach ( $user -> accounts as $account )
        {
            foreach ( $account -> projects as $project )
            {
                $project -> is_owner = $account -> pivot -> is_owner;
                $projects[] = $project;
            }
        }

        return fractal() -> collection( $projects, new VstsProjectTransformer );
    }


    public function show( VstsProject $vstsProject )
    {
        return fractal() -> parseIncludes( [ 'members' ] ) -> item( $vstsProject, new VstsProjectTransformer );
    }


    public function enrol( ProjectEnrolmentRequest $request, VstsProject $vstsProject )
    {
        // Add web hook
        $this -> vstsService -> addWebHook( Helper::currentUser(), $vstsProject );

        // Add module id to project
        $vstsProject -> module_id = $request -> module_id;
        $vstsProject -> save();
    }
}