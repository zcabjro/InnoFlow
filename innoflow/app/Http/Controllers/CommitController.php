<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 11/01/2017
 * Time: 12:12
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Commit;


class CommitController extends Controller
{
    public function create( Request $request )
    {
        $commit = new Commit();
        $commit -> save();
    }
}