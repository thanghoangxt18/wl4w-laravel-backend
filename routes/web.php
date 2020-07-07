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


//api
$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->group(['prefix' => '/auth'], function () use ($router) {
        $router->post('/register', 'AuthController@register');
        $router->post('/login', 'AuthController@login');
        $router->post('/detail', 'AuthController@detail');
    });

     $router->post('get-list-zone', 'ZoneController@getZoneList');//1
    $router->post('get-course-by-zone','ZoneController@getCourseByZone');//2
    $router->post('get-groups-exercise', 'GroupController@getAllGroupAndItsExercise');
    $router->get('/check', 'AuthController@checkConnection');
    $router->get('get-all-zones', 'ZoneController@getAllZones');
    $router->get('getexofagroup', 'GroupController@getAllExerciseOfAGroupByGroupId');
    $router->get('getallgroupanditsexercise', 'GroupController@getAllGroupAndItsExercise');
});
