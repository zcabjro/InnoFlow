<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/*Route::get( '/authorize', 'VSTSController@authorizeApp' );
Route::get( '/authorize-callback', 'VSTSController@authorizeAppCallback' );*/

Route::post( '/login', "AuthController@loginUser" );
Route::post( '/register', "AuthController@registerUser" );


Route::group( [ 'middleware' => 'jwt-auth' ], function () {

    Route::post( '/commits', 'CommitController@create' );

});