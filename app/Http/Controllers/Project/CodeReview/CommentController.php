<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 04/03/2017
 * Time: 16:58
 */

namespace App\Http\Controllers\Project\CodeReview;

use App\Events\CodeReviewActiveEvent;
use App\Events\CodeReviewCreatedEvent;
use App\Events\CommentCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\NewCommentRequest;
use App\Models\CodeReview;
use App\Models\Comment;
use App\Models\VstsProject;
use App\Repositories\Comment\CommentRepoInterface;
use App\Transformers\CommentTransformer;


class CommentController extends Controller
{
    private $commentRepo;


    function __construct( CommentRepoInterface $commentRepo )
    {
        $this -> commentRepo = $commentRepo;
    }


    public function store( VstsProject $vstsProject, CodeReview $codeReview, NewCommentRequest $request )
    {
        $comment = $this -> commentRepo -> create( $request -> all() );

        event( new CodeReviewActiveEvent( $codeReview, $vstsProject ) );

        event( new CommentCreatedEvent( $vstsProject ) );

        return fractal() -> item( $comment, new CommentTransformer );
    }


    public function index( VstsProject $vstsProject, CodeReview $codeReview )
    {
        $comments = $codeReview -> comments() -> orderBy( 'created_at', 'DESC' ) -> get();

        return fractal() -> collection( $comments, new CommentTransformer );
    }


    public function show( VstsProject $vstsProject, CodeReview $codeReview, Comment $comment )
    {
        return fractal() -> item( $comment, new CommentTransformer );
    }
}