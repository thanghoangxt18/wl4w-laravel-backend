<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
/** @var $router \Illuminate\Support\Facades\Route */
$router->group(['prefix' => '/api/v1'], function () use ($router) {
    $router->group(['prefix' => '/auth'], function () use ($router) {
        $router->post('/register', 'AuthController@register');
        $router->post('/login', 'AuthController@login');
        $router->post('/detail','AuthController@detail');
    });

    $router->get('/check','AuthController@checkConnection');
    $router->get('getallzones','ZoneController@getAllZones');
    $router->get('getexofagroup','GroupController@getAllExerciseOfAGroupByGroupId');
    $router->get('getallgroupanditsexercise','GroupController@getAllGroupAndItsExercise');

    /*
     * exercise api
     */
    $router->get('/get-detail-exercise/{id}','ExerciseController@getDetailExercise');
});
