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

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->group(['prefix' => '/auth'], function () use ($router) {
        $router->post('/register', 'AuthController@register');
        $router->post('/login', 'AuthController@login');
        $router->post('/detail', 'AuthController@detail');
    });
    //api 1
    $router->post('get-list-zone', 'ZoneController@getZoneList');
    //api 2
    $router->post('get-course-by-zone','ZoneController@getCourseByZone');
    //api 3
    $router->post('get-course-item','ZoneController@getCourseItem');
    //api 4
    $router->post('get-detail-group-workout','ZoneController@getDetailGroupWorkout');
    //api 5
    $router->post('get-detail-exercise', 'ExerciseController@getDetailExercise');
    //api 6
    $router->post('get-list-discover-course', 'CourseController@getListDiscoverCourse');
    //api 7
    $router->post('search-discover', 'CourseController@searchDiscover');

});
