<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 04/03/2017
 * Time: 14:31
 */

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\CodeReview\NewCodeReviewRequest;
use App\Models\CodeReview;
use App\Models\VstsProject;
use App\Repositories\CodeReview\CodeReviewRepoInterface;
use App\Services\Common\Helper;
use App\Transformers\CodeReviewTransformer;


class CodeReviewController extends Controller
{
    private $codeReviewRepo;


    public function __construct( CodeReviewRepoInterface $codeReviewRepo )
    {
        $this -> codeReviewRepo = $codeReviewRepo;
    }


    public function store( VstsProject $vstsProject, NewCodeReviewRequest $request )
    {
        $data = $request -> all();
        $data[ 'user_id' ] = Helper::currentUser() -> user_id;
        $data[ 'project_id' ] = $vstsProject -> project_id;

        $codeReview = $this -> codeReviewRepo -> create( $data );

        $codeReview -> commits() -> attach( $request -> commitIds );
    }


    public function index( VstsProject $vstsProject )
    {
        $codeReviews = $vstsProject -> codeReviews;
        return fractal() -> collection( $codeReviews, new CodeReviewTransformer );
    }


    public function show( VstsProject $vstsProject, CodeReview $codeReview )
    {
        return fractal() -> parseIncludes( [ 'commits', 'comments' ] ) -> item( $codeReview, new CodeReviewTransformer );
    }
}