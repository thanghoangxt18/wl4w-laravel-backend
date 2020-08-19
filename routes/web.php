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

    $router->group(['middleware' => 'jwt.auth'], function ($router) {
        //api 1
        $router->post('get-list-zone', 'ZoneController@getZoneListAndExercise');
        //api 2
        $router->post('get-course-by-zone', 'ZoneController@getCourseByZone');
        //api 3
        $router->post('get-course-item', 'ZoneController@getCourseItem');
        //api 4
        $router->post('get-detail-group-workout', 'ZoneController@getDetailGroupWorkout');
        //api 5
        $router->post('get-detail-exercise', 'ExerciseController@getDetailExercise');
        //api 6
        $router->post('get-list-discover-course', 'CourseController@getListDiscoverCourse');
        //api 7
        $router->post('search-discover', 'CourseController@searchDiscover');
        //api 10
        $router->post('create-new-exercise', 'ExerciseController@createNewExercise');
        //api 11
        $router->post('get-exercise-by-keyword', 'ExerciseController@getExerciseByKeyword');
        //api 12
        $router->post('update-exercise', 'ExerciseController@updateExercise');
        //api 13
        $router->post('delete-exercise', 'ExerciseController@deleteExercise');
        //api 14
        $router->post('create-new-group', 'GroupController@createNewGroup');
        //api 15
        $router->post('get-group-by-keyword', 'GroupController@getGroupByKeyword');
        //api 16
        $router->post('update-group', 'GroupController@updateGroup');
        //api 17
        $router->post('delete-group', 'GroupController@deleteGroup');
        //api 18
        $router->post('create-new-zone', 'ZoneController@createNewZone');
        //api 19
        $router->post('get-zone-by-keyword', 'ZoneController@getZoneByKeyword');
        //api 20
        $router->post('update-zone', 'ZoneController@updateZone');
        //api 21
        $router->post('delete-zone', 'ZoneController@deleteZone');
        //api 22
        $router->post('create-new-course', 'CourseController@createNewCourse');
        //api 23
        $router->post('get-basic-of-course', 'CourseController@getBasicOfCourse');
        //api 24
        $router->post('update-basic-of-course', 'CourseController@updateBasicCourse');
        //api 25
        $router->post('get-items-by-course', 'CourseController@getItemsByCourse');
        //api 26
        $router->post('create-new-item', 'CourseController@createNewItem');
        //api 27
        $router->post('update-basic-item', 'CourseController@updateBasicItem');
        //api 28
        $router->post('add-new-group-to-item', 'CourseController@addNewGroupToItem');
        //api 29
        $router->post('delete-group-of-item', 'CourseController@deleteGroupOfItem');
        //api 30
        $router->post('delete-item', 'CourseController@deleteItem');
        //api 31
        $router->post('delete-course', 'CourseController@deleteCourse');
        //api 32
        $router->post('add-new-exercise-to-group', 'GroupController@addNewExerciseToGroup');
        //api 33
        $router->post('delete-exercise-of-group', 'GroupController@deleteExerciseOfGroup');
        //api 34
        $router->post('add-new-exercise-to-zone', 'ZoneController@addNewExerciseToZone');
        //api 35
        $router->post('delete-exercise-of-zone', 'ZoneController@deleteExerciseOfZone');
        //api 36
        $router->post('get-exercise-of-zone', 'ZoneController@getExerciseOfZone');
        //api 37
        $router->post('get-zone-list', 'ZoneController@getZoneList');
        //api 38
        $router->post('get-exercise-list', 'ExerciseController@getExerciseList');
        //api 39
        $router->post('get-group-list', 'GroupController@getGroupList');
        //api 40
        $router->post('get-course-list', 'CourseController@getCourseList');
        //api 41
        $router->post('get-course-by-keyword', 'CourseController@getCourseByKeyword');
    });

});
