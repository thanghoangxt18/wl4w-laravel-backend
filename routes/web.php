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
        //api 8
        $router->post('/login', 'AuthController@login');
        //api 9
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
    //api 10
    $router->post('create-new-exercise','ExerciseController@createOrUpdateExercise');

    //api 11
    $router->post('get-exercise-by-keyword','ExerciseController@getExerciseByKeyword');
    //api 12
    $router->post('update-exercise','ExerciseController@createOrUpdateExercise');
    //api 13
    $router->post('delete-exercise','ExerciseController@deleteExercise');
    //api 14
    $router->post('create-new-group','GroupController@createNewGroup');
    //api 15
    $router->post('get-group-by-keyword','GroupController@getGroupByKeyword');
    //api 16
    $router->post('update-group','GroupController@updateGroup');
    //api 17
    $router->post('delete-group','GroupController@deleteGroup');
    //api 18
    $router->post('create-new-zone','ZoneController@createNewZone');
    //api 19
    $router->post('get-zone-by-keyword','ZoneController@getZoneByKeyword');
    //api 20
    $router->post('update-zone','ZoneController@updateZone');
    //api 21
    $router->post('delete-zone','ZoneController@deleteZone');
});
