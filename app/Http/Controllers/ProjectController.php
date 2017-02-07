<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 31/01/2017
 * Time: 20:40
 */

namespace App\Http\Controllers;


class ProjectController extends Controller
{
    public function index()
    {
        $projects = [];
        $projects[] = ['id' => 1, 'name' => 'InnoFlow' ];
        $projects[] = ['id' => 2, 'name' => 'AuctionHouse' ];
        $projects[] = ['id' => 3, 'name' => 'Freshly' ];
        $projects[] = ['id' => 4, 'name' => 'SnapPro' ];

        return $projects;
    }
}