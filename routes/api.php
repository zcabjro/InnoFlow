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


// Auth routes
Route::post( 'login', 'AuthController@loginUser' );
Route::post( 'register', 'AuthController@registerUser' );
Route::get( 'innoflow', 'AuthController@isLoggedIn' );


// InnoFlow extension routes
Route::post( 'innovations', 'InnovationController@store' );


// Public routes
Route::post( 'commits', 'CommitController@store' );
Route::get( 'users/search', 'UserController@search' );
Route::get( 'users/{user}/innovations', 'User\InnovationController@index' );


// JWT token protected routes
Route::group( [ 'middleware' => 'jwt-auth' ], function () {

    Route::get( 'logout', 'AuthController@logoutUser' );
    Route::get( 'vsts', 'AuthController@isVstsAuthorized' );

    Route::get( 'innovations', 'InnovationController@index' );

    Route::post( 'classes', 'ModuleController@store' );
    Route::get( 'classes', 'ModuleController@index' );
    Route::get( 'classes/search', 'ModuleController@search' );
    Route::get( 'classes/{module}', 'ModuleController@show' ) -> middleware( 'module-auth' );
    Route::get( 'classes/admins/search', 'ModuleController@searchAdmin' );

    Route::get( 'projects', 'ProjectController@index' ) -> middleware( 'vsts-auth' );
    Route::post( 'projects/{vstsProject}/enrol', 'ProjectController@enrol' ) -> middleware( 'vsts-auth' );

    Route::group( [ 'middleware' => 'project-auth' ], function () {

        Route::get( 'projects/{vstsProject}', 'ProjectController@show' );

        Route::get( 'projects/{vstsProject}/commits', 'Project\CommitController@index' );
        Route::get( 'projects/{vstsProject}/commits/{commit}', 'Project\CommitController@show' );

        Route::post( 'projects/{vstsProject}/codereviews', 'Project\CodeReviewController@store' );
        Route::get( 'projects/{vstsProject}/codereviews', 'Project\CodeReviewController@index' );
        Route::get( 'projects/{vstsProject}/codereviews/{codeReview}', 'Project\CodeReviewController@show' );

        Route::post( 'projects/{vstsProject}/codereviews/{codeReview}/comments', 'Project\CodeReview\CommentController@store' );
        Route::get( 'projects/{vstsProject}/codereviews/{codeReview}/comments', 'Project\CodeReview\CommentController@index' );
        Route::get( 'projects/{vstsProject}/codereviews/{codeReview}/comments/{comment}', 'Project\CodeReview\CommentController@show' );

        Route::get( 'projects/{vstsProject}/metrics', 'Project\MetricController@index' );
    });

});