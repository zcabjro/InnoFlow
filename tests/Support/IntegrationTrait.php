<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 25/01/2017
 * Time: 13:07
 */

namespace Test\Support;

use Illuminate\Http\Response;


trait IntegrationTrait
{
    /**
     * Makes a HTTP request to an api route and checks the returned http code with the expected code.
     *
     * @param string $method
     * @param string $route
     * @param array $parameters
     * @param int $code
     *
     * @return void
     */
    public function callRoute( $method, $route, array $parameters, $code )
    {
        $response = $this -> call( $method, $route, $parameters );
        $this -> assertEquals( $code, $response -> status() );
    }
}