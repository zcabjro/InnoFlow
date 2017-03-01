<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 11/01/2017
 * Time: 12:12
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commit;


class CommitController extends Controller
{
    public function store( Request $request )
    {
        if ( !$request -> eventType == 'git.push' )
        {
            return;
        }

        $commit = new Commit();
        $commit -> commit_id = $request -> resource[ 'commits' ][ 'commitId' ];
        $commit -> repository_url =$request -> resource[ 'repository' ]['url'];
        $commit -> save();
    }
}