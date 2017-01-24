<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 18/01/2017
 * Time: 19:03
 */


namespace App\Traits;


trait JsonResponseTrait
{
    /**
     * @var
     */
    private $statusCode;



    /**
     * @return mixed
     */
    private function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    private function setStatusCode( $statusCode )
    {
        $this->statusCode = $statusCode;
        return $this;
    }



    /**
     * @param $data
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    private function respond( $data, $headers = [] )
    {
        return response() -> json( $data, $this -> getStatusCode(), $headers );
    }

    /**
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    private function respondWithError( $message )
    {
        return $this -> respond( [
            'error' => $message
        ] );
    }



    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondBadRequest( $message )
    {
        return $this -> setStatusCode( 400 ) -> respondWithError( $message );
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondUnauthorized( $message )
    {
        return $this -> setStatusCode( 401 ) -> respondWithError( $message );
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondForbidden( $message )
    {
        return $this -> setStatusCode( 403 ) -> respondWithError( $message );
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondNotFound( $message )
    {
        return $this -> setStatusCode( 404 ) -> respondWithError( $message );
    }



    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondInternalError( $message = "Server error" )
    {
        return $this -> setStatusCode( 500 ) -> respondWithError( $message );
    }
}