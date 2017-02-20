<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 19/02/2017
 * Time: 18:55
 */


namespace App\Transformers;

use App\Models\Module;
use League\Fractal\TransformerAbstract;


class ModuleTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'admins', 'projects'
    ];

    protected $defaultIncludes = [

    ];


    public function transform( Module $module )
    {
        return [

            'name' => $module -> name,
            'description' => $module -> description,
            'code' => $module -> code

        ];
    }


    /**
     * Include admins
     *
     * @param Module $module
     * @return \League\Fractal\Resource\Collection
     */
    public function includeAdmins( Module $module )
    {
        $admins = $module -> admins;
        return $this -> collection( $admins, new UserTransformer );
    }


    /**
     * Include projects.
     *
     * @param Module $module
     * @return \League\Fractal\Resource\Collection
     */
    public function includeProjects( Module $module )
    {
        $projects = $module -> projects;
        return $this -> collection( $projects, new VstsProjectTransformer );
    }
}
