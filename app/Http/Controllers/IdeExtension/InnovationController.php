<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 26/01/2017
 * Time: 23:48
 */

namespace App\Http\Controllers\IdeExtension;


use App\Http\Controllers\Controller;
use App\Http\Requests\IdeExtension\InnovationRequest;
use App\Models\Innovation;


class InnovationController extends Controller
{
    public function store( InnovationRequest $request )
    {
        $innovation = new Innovation();
        $innovation -> save();
    }
}