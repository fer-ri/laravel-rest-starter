<?php

$api->group(['middleware' => 'api.auth'], function ($api) {
    $api->get('/users', 'UserController@index');
    $api->get('/users/me', 'UserController@me');
    $api->post('/users', 'UserController@store');
    $api->get('/users/{uuid}', 'UserController@show');
    $api->put('/users/{uuid}', 'UserController@update');
    $api->delete('/users/{uuid}', 'UserController@destroy');
});
