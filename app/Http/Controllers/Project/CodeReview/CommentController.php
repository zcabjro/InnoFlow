<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 04/03/2017
 * Time: 16:58
 */

namespace App\Http\Controllers\Project\CodeReview;

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
        if ( !$codeReview -> is_active )
        {
            $codeReview -> is_active = true;
            $codeReview -> save();
        }

        $this -> commentRepo -> create( $request -> all() );
    }


    public function index( VstsProject $vstsProject, CodeReview $codeReview )
    {
        $comments = $codeReview -> comments;
        return fractal() -> collection( $comments, new CommentTransformer );
    }


    public function show( VstsProject $vstsProject, CodeReview $codeReview, Comment $comment )
    {
        return fractal() -> item( $comment, new CommentTransformer );
    }
}