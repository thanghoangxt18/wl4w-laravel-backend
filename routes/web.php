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
/** @var $router */
$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => '/auth'], function () use ($router) {
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
    $router->post('/detail','AuthController@detail');
});

//api
$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->post('get-list-zone','ZoneController@getZoneList');
    $router->post('get-groups-exercise','GroupController@getAllGroupAndItsExercise');
});
//
$router->get('/check','AuthController@checkConnection');
$router->get('getallzones','ZoneController@getAllZones');
$router->get('getexofagroup','GroupController@getAllExerciseOfAGroupByGroupId');
$router->get('getallgroupanditsexercise','GroupController@getAllGroupAndItsExercise');
