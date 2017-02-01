<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 11/01/2017
 * Time: 12:12
 */

namespace App\Http\Controllers\Commit;

use Illuminate\Http\Request;
use App\Models\Commit;
use App\Http\Controllers\Controller;

class CommitController extends Controller
{
    public function create( Request $request )
    {
        $commit = new Commit();
        $commit -> save();
    }
}